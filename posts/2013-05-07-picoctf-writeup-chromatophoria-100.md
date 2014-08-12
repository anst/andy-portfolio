!@picoCTF Writeup Chromatophoria - 100
<p>Chromatophoria was an interesting problem. You couldn't un-steganography the image with any default program like S-Tools. We found that out the hard way. After trying all of the possible stego programs, We decided to actually *read* the hint and do our own LSB steganography. </p>

<p>The trick to this, as pointed out by one of the mods, was to actually concat the LSBs in B-G-R order instead of the de facto R-G-B order. This may have something to do with Java (I don't understand you). While my solution was in Java, though inherently slow, we were able to output an 11MB file filled with the encoded message and approximately 900000 zeros. </p>

<p>Chopping off the rest of the zeros, we got a nice binary message. Once we converted that to text we got:</p>
<pre>
Hey I think we can write safely in this file without anyone seeing it. Anyway, the secret key is: st3g0_saurus_wr3cks.
</pre>
<p>Obviously the key must be:</p>
<code>st3g0_saurus_wr3cks</code>			