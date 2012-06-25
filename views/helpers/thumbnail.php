<?php 

App::import('Vendor', 'phpthumb', array('file' => 'phpThumb'.DS.'phpthumb.class.php'));

class ThumbnailHelper extends AppHelper {
	private $pt; //pointer for the phpthumb instance
	private $error;	//error messages, if any
	private $options = array(
		'width'		=> 150,
		'height'	=> NULL,
		'scale'		=> true,
		'crop'		=> false,
		'cacheDir'	=> '/img/cache',
		'displayDir'	=> '/img/cache',
		'errorImage'	=> '/img/missing_image.png'
	);
	private $tagOptions = array(
		'alt'		=> NULL,
	);
	private $filename;
	private $cacheFile;

	public function show($img, $options=array(), $tagOptions=array()) {
		$this->filename = $img;
		$this->init($options, $tagOptions);
		return $this->generateTag();
	}

	protected function init($options, $tagOptions) {
		$this->options = array_merge($this->options, $options);
		$this->tagOptions = array_merge($this->tagOptions, $tagOptions);
		$this->setCacheFilename();
	}

	protected function setCacheFilename() {
		$ext = strrchr($this->filename, '.');
		$this->cacheFile = $this->options['cacheDir'] . DS . md5( $this->filename . serialize($this->options) ).$ext;
	}

	protected function isCached() {
		return (file_exists($this->cacheFile) && is_readable($this->cacheFile));
	}

	protected function generateThumb() {
		if(!class_exists('phpThumb'))
			die('phpThumb class not installed. Expected at /vendors/phpThumb'.DS.'phpthumb.class.php');

		//check for the cached file. if it's not there, create it
		if(! $this->isCached()) {
			//associate the helper options with the php_thumb options
			$opts = array(
				'src'	=> $this->filename,
				'w'	=> $this->options['width'],
				'h'	=> $this->options['height'],
				'q'	=> 90, //always use quality of 90
				'zc'	=> 1,
				'save_path'	=> $this->options['cacheDir'],
				'display_path'	=> $this->options['displayDir'],
				'error_image_path'	=> $this->options['errorImage']
			);

			$this->pt = new phpThumb();
			foreach($this->pt as $var => $value) {
				if(isset($opts[$var]))    {
					$this->pt->setParameter($var, $opts[$var]);
				}
			}

			if($this->pt->GenerateThumbnail()) {
				if(! $this->pt->RenderToFile($this->cacheFile))
					$this->error = $this->pt->debugmessages;
			} else {
				//$this->error = ereg_replace("[^A-Za-z0-9\/: .]", "", $this->pt->fatalerror);
				//$this->error = str_replace('phpThumb v1.7.8200709161750', '', $this->error);
				$this->error = $this->pt->DebugMessage."\n".$this->pt->fatalerror;
			}
		}
	}

	protected function generateTag() {
		$this->generateThumb();

		if(! empty($this->error)) { //an error occurred
			debug($this->error);

		} else {

			$tag = '<img src="'.$this->options['displayDir'].$this->cacheFile.'" ';
			foreach($this->tagOptions as $attr => $value) {
				if(! in_array($attr, array('src', 'width', 'height')))
					$tag .= "{$attr}=\"{$value}\" ";
			}
			
			if(!empty($this->options['width']))
				$tag .= 'width="'.$this->options['width'].'" ';
			if(!empty($this->options['height']))
				$tag .= 'height="'.$this->options['height'].'" ';

			$tag .= '/>';

		}

		return $tag;
	}

}
?>
