!@picoCTF Writeup Black Hole - 115
This problem gave us a hard time. I'm going to assume you were able to grab <code>masked_key.png</code> from the .img or from the shell server. We just SFTP'd into the shell server and downloaded it from there. So you're faced with the corrupted image, what do you do? Naturally, you hex dump it and tediously search through the file. If you hexdump and pipe it into tail you notice something interesting. 
<pre class="prettyprint">
ff d8 ff e0-00 10 4a 46-49 46 00 45-56 45 4e 54  ......JF IF.EVENT
48 4f 52 49-5a 4f 4e 20-45 56 45 4e-54 48 4f 52  HORIZON. EVENTHOR
49 5a 4f 4e-20 45 56 45-4e 54 48 4f-52 49 5a 4f  IZON.EVE NTHORIZO
4e 20 45 56-45 4e 54 48-4f 52 49 5a-4f 4e ff d9  N.EVENTH ORIZON..
</pre>
When we saw this, we thought we had found the key, but unfortunately it was not the case. So then we noticed something else... what about that <code>JFIF</code> whether it was a coincidence or not, JFIF stands for JPEG File Interchange Format. So, I thought of JPGs and what their regular header is, and after a quick Google search I realized JPG files regularly start with <code>ffd8</code> and ends with <code>ffd9</code>, which matches the hex dump perfectly. We looked closer at the file name and noticed the term "masked", immediately thought bit masking, but we realized that you can't bitshift anything and get plaintext junk at the end. So we assumed it must have been XOR cypher. So we thought, if the file was called <b>masked</b>_key.png the original must be the mask, so we need to repeat the "key", so to speak, for the entire length of the file. We used a program called XORFiles, but you could have written your own solution like this:
<pre class="prettyprint">
import re, os
fcontent = re.sub('\s','',open('bin_data.txt').read())
repkey = "ffd8ffe000104a464946004556454e54484f52495a4f4e204556454e54484f52495a4f4e204556454e54484f52495a4f4e204556454e54484f52495a4f4effd9"
while len(repkey) < len(fcontent):
	repkey += repkey
filenum = int(fcontent,16)
keynum = int(repkey,16)
result = filenum ^ keynum
hexresult = hex(result)[2:][:-1]
open('blackhole.png','w+').write(hexresult.decode('hex'))
</pre>
In the end the image contained the key in plain view:
<code>Hacking Radiation</code>.			