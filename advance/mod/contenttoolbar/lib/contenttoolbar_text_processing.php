<?php
/*
 * This script process the provieded string for video tags
 * It is called with run("video:text:process",$parameter)
 * 
 * Created on Apr 5, 2007
 * 
 * @param string $parameter String to be processed
 * @param string $run_result String with the result
 * 
 * @author Diego Andr�s Ram�rez Arag�n <diego@somosmas.org>
 * @copyright Diego Andr�s Ram�rez Arag�n - 2007
*/
if (isset ($parameter)) {
            $functionbody = <<< END
                
                \$width = 400;
                \$height = 300;
		\$url = trim(\$matches[1]);
                \$url = str_replace("&amp;","&",\$url);
		\$url = preg_replace("/youtube.com\/watch\?v\=/","youtube.com/v/",\$url); // youtube
		\$url = preg_replace("/video.google.com\/videoplay/","video.google.com/googleplayer.swf",\$url); // google video
                preg_match("/(.*)\@\@(\d+)x(\d+)/i",\$url,\$size);
                if(count(\$size)==4){
                  \$url = \$size[1];                  
                  \$width = \$size[2];
                  \$height = \$size[3];
                }
                
                return "<object width=\"\$width\" height=\"\$height\"><param name=\"movie\" value=\"\$url\"></param><param name=\"wmode\" value=\"transparent\"></param><embed class=\"VideoPlayback\" type=\"application/x-shockwave-flash\" src=\"\$url\" width=\"\$width\" height=\"\$height\"/></object>";
                
END;
 $run_result = preg_replace_callback("/\{\{video:([^}]+)\}\}/i",create_function('$matches',$functionbody),$parameter);
}
?>
