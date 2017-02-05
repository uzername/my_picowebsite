---
Title: PHP: Adding capability for MarkdownExtra to add html classes and ids to Fenced code blocks
---
#Styling Fenced code blocks with classes and ids{.content-h1}
Here is the required patch file for MarkDown Extended parser of erusev:

~~~ {.content-codeblock}
From f058dec6b3b2534e71d7fc39b19b6d0217899778 Mon Sep 17 00:00:00 2001
From: uzername <ivanpost777@gmail.com>
Date: Fri, 3 Feb 2017 14:32:12 +0200
Subject: [PATCH 1/1] Code block and other styling

---
 vendor/erusev/parsedown-extra/ParsedownExtra.php | 44 ++++++++++++++++++++++++
 1 file changed, 44 insertions(+)

diff --git a/vendor/erusev/parsedown-extra/ParsedownExtra.php b/vendor/erusev/parsedown-extra/ParsedownExtra.php
index be6966d..8c94b57 100644
--- a/vendor/erusev/parsedown-extra/ParsedownExtra.php
+++ b/vendor/erusev/parsedown-extra/ParsedownExtra.php
@@ -217,7 +217,51 @@ class ParsedownExtra extends Parsedown
 
         return $Block;
     }
+    /*Jovan edit starts here: 030217 fenced code blocks*/
+    protected function blockFencedCode($Line) 
+    {
+        #there's a big problem with adding css classes and tags to fenced code blocks. The regexp should be changed
+        if (preg_match('/^['.$Line['text'][0].']{3,}[ ]*([\w-]+)?[ ]*{('.$this->regexAttribute.'+)}[ ]*$/', $Line['text'], $matches, PREG_OFFSET_CAPTURE))
+        {
+            
+            $Element = array(
+                'name' => 'code',
+                'text' => '',
+            );
+            if (isset($matches[1]))
+            {
+                $class = 'language-'.$matches[1][0];
 
+                $Element['attributes'] = array(
+                    'class' => $class,
+                );
+            }
+            $Block = array(
+                'char' => $Line['text'][0],
+                'element' => array(
+                    'name' => 'pre',
+                    'handler' => 'element',
+                    'text' => $Element,
+                ),
+            );
+            # Jovan edit. If available, add attributes
+            if (isset($matches[2][0]))
+            {
+                $Block['element']['attributes'] = $this->parseAttributeData($matches[2][0]);
+            }
+            return $Block;
+        }
+
+    }
+
+    protected function blockFencedCodeComplete($Block)
+    {
+        $Block = parent::blockFencedCodeComplete($Block);
+        
+        
+        return $Block;
+    }
+    /*Jovan edit ends here*/
     #
     # Markup
 
-- 
1.7.11.msysgit.0


~~~