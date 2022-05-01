<?php

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
	$cut = imagecreatetruecolor($src_w, $src_h);
	imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
	imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
	imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

function generate_avatar($name) {

	$img_x = 1024;
	$img_y = 1024;
	$hash = crc32($name);
	srand($hash);

	// Background
	$rand_bg = rand(1, 20);
	$im = imagecreatefromwebp("backgrounds/$rand_bg.webp");
	$im = imagescale($im, 1024, 1024);

	// Accessory
	$accessory_rand = rand(0, 15);
	$im_accessory = imagecreatefromwebp("sets/accessories/$accessory_rand.webp");

	// Body
	$body_rand = rand(0, 14);
	$im_body = imagecreatefromwebp("sets/body/$body_rand.webp");

	// Eyes
	$eyes_rand = rand(0, 14);
	$im_eyes = imagecreatefromwebp("sets/eyes/$eyes_rand.webp");

	// Fur
	$fur_rand = rand(0, 9);
	$im_fur = imagecreatefromwebp("sets/fur/$fur_rand.webp");

	// Mouth
	$mouth_rand = rand(0, 9);
	$im_mouth = imagecreatefromwebp("sets/mouth/$mouth_rand.webp");

	// Merge all images
	imagecopymerge_alpha($im, $im_body, 0, 0, 0, 0, 1024, 1024, 100);
	imagecopymerge_alpha($im, $im_fur, 0, 0, 0, 0, 1024, 1024, 100);
	imagecopymerge_alpha($im, $im_accessory, 0, 0, 0, 0, 1024, 1024, 100);
	imagecopymerge_alpha($im, $im_mouth, 0, 0, 0, 0, 1024, 1024, 100);
	imagecopymerge_alpha($im, $im_eyes, 0, 0, 0, 0, 1024, 1024, 100);

	$im = imagescale($im, $img_x, $img_y);
	header("Content-Type: image/jpeg");
        imagejpeg($im, NULL, 100);
        $rawImageBytes = ob_get_clean();
        echo "<img src='data:image/jpeg;base64," . base64_encode( $rawImageBytes ) . "' />";


	imagedestroy($im);
}

echo generate_avatar($_GET['name']);