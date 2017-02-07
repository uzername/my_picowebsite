---
Title: Tinkering: Long forgotten notes about editing video and making WEBM using a command line tool ffmpeg
UseToken: TRUE
tokenpath: access1
---
#Using ffmpeg: a practical example{.content-h1}

This code means a lot. It gives an example of how to use ffmpeg to make WEBM files.
~~~ {.content-codeblock}
ffmpeg -i "E:\Internet\webm_experiment\movie.webm" "E:\Internet\webm_experiment\movie1.mp4"

ffmpeg -i "E:\Internet\webm_experiment\LH.3gp" -vn -f mp3  "E:\Internet\webm_experiment\LHs1.mp3"

ffmpeg -ss 01:11 "E:\Internet\webm_experiment\LHs_original.mp3" -t 32.4 "E:\Internet\webm_experiment\LHs_crop1.mp3"

ffmpeg -ss 00:00:00 -i "E:\Internet\webm_experiment\movie1.mp4" -t 00:02.95 -vn -f mp3 "E:\Internet\webm_experiment\movie1s_crop1.mp3"

ffmpeg -i "movie1.mp4" -i "LHs_crop1.mp4" -map 0:v -map 1:a -codec copy "movie_final.mp4"
~~~ 

Better use [WebmConverter](https://github.com/WebMBro/WebMConverter).