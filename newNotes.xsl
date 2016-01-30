<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
  <body>
  <h4 id='allNoteDisplay'>New Notes</h4>
    <div id="allNoteDisplay">

      <xsl:for-each select="collection/notes/note">
		<xsl:if test="status[text()='New']">
            <div>
              <div><xsl:value-of select="note/id"/></div>
              <div>Subject: <xsl:value-of select="subject"/></div>
              <div>From: <xsl:value-of select="sender"/></div>
              <div>To: <xsl:value-of select="recipients"/></div>
              <div><xsl:value-of select="date"/></div>
              <div><xsl:value-of select="message"/></div>
			  <div><a href="{link}"><xsl:value-of select="link"/></a></div>
              <div>Note Status: <xsl:value-of select="status"/></div>
            </div><br></br>
		</xsl:if>
      </xsl:for-each>
	  
    </div>
  </body>
  </html>
</xsl:template>
</xsl:stylesheet>