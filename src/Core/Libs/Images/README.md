Image Resize
============

#### Simple way to resize image files.

Supported file types:

1. jpg / jpeg
2. gif
3. png

#### Usage:
Basic:
```php
$image = new Image();
$image->get('image.jpg')->resize(300,200)->save();

```

```php
$image = new Image();
$image->get('image.jpg')->resize(300,200)->withPreffix('resize_')->save();
```
Save with new name:
```php
$image = new Image();
$image->get('image.jpg')->resize(300,200)->save(dir/new_image.jpg);

```
Resize & move resized file in new directory:
```php
$image = new Image();
$image->get('image.jpg')->resize(300,200)->move('images/');
// Add preffix and move resized file
$image->get('image.jpg')->resize(300,200)->withPreffix('resize_')->move('images/');
```

Get image info after resize:
```php
$image = new Image();
$image->get('image.jpg')->resize(300,200)->save();

print_r($image->imageinfo('file_path'));
print_r($image->imageinfo('width'));
print_r($image->imageinfo('height'));
print_r($image->imageinfo('mime'));
print_r($image->imageinfo('html')); // string: (width="300" height="200")

```
