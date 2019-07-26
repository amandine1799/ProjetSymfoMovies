#!/bin/bash

IMAGE_PATH=public/img

for img in $IMAGE_PATH/*.jpg; do
    convert -resize 250x "$img" "${IMAGE_PATH}/min/$(basename $img)"
done

echo "C'est terminé !"
