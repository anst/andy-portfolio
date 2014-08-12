!@picoCTF Writeup PHP4 - 110
<p>Another albeit, <i>interesting</i> MySQL injection problem. First, lets look at the actual exploitable part of the code:
<pre class="prettyprint">
  $pass = md5($<em>POST[pass]);
  $query = @mysql</em>fetch<em>array(mysql</em>query("select pw from php3 where user='$user'"));
</pre>
As you can see, <code>$pass</code> is being encrypted with MD5. Not really a problem, more of a roadblock. So what can we do in this situation? First off, the natural thing to do is to prevent the execution of the SQL query and inject your own malicious query instead. You could do it several ways, but here is what we came up with:
<pre class="prettyprint">
l33t' and 'haxx'='hacks' union all select 'e1568c571e684e0fb1724da85d215dc0
</pre>
The injection query will have to be placed in the Username field this time, and <code>l33t</code> in Password field.
<br>When doing <code>and 'haxx'='hacks'</code> on the left half of the query, you are essentially making that side evaluate to false, thus removing any sort of reference to it. <code>union all</code> combines the two SQL queries (nothing and the exploit query). Thus, we have what would look like a valid SQL query:
<pre class="prettyprint">
select 'e1568c571e684e0fb1724da85d215dc0
</pre>
Which successfully selects the hashed password <code>l33t</code> into an array.
<pre class="prettyprint">
 if (($query[pw]) &amp;&amp; (!strcasecmp($pass, $query[pw]))) {
</pre>
When the comparison is done, everything is valid and you are successfully able to get the key.
<pre>
Logged in! Key: 50c90a07790d4d0ab7fc7f695cb61d0e
</pre>
So, most certainly, the key is:
<code>
50c90a07790d4d0ab7fc7f695cb61d0e
</code>.