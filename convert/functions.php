<?php 

function download(string $path): void
{
   $filename = basename($path);
   $filesize = filesize($path);
   $headers = [
         'Cache-Control: must-revalidate',
         'Content-Description: File Transfer',
         "Content-Disposition: attachment; filename={$filename}",
         "Content-Length: {$filesize}",
         'Content-Type: application/pdf',
         'Expires: 0',
         'Pragma: public',
   ];
   foreach ($headers as $key) {
         header($key);
   }
   readfile($path);
}