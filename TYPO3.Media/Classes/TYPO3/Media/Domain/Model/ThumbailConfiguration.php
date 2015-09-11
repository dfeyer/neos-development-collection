<?php
namespace TYPO3\Media\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Media".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * An Thumbail Configuration
 */
class ThumbailConfiguration {

	/**
	 * @var integer
	 */
	protected $width;

	/**
	 * @var integer
	 */
	protected $maximumWidth;

	/**
	 * @var integer
	 */
	protected $height;

	/**
	 * @var integer
	 */
	protected $maximumHeight;

	/**
	 * @var boolean
	 */
	protected $allowCropping;

	/**
	 * @var boolean
	 */
	protected $allowUpScaling;

	/**
	 * ImageConfiguration constructor.
	 * @param integer $width
	 * @param integer $maximumWidth
	 * @param integer $height
	 * @param integer $maximumHeight
	 * @param boolean $allowCropping
	 * @param boolean $allowUpScaling
	 */
	public function __construct($width = NULL, $maximumWidth = NULL, $height = NULL, $maximumHeight = NULL, $allowCropping = FALSE, $allowUpScaling = FALSE) {
		$this->width = $width ? (integer)$width : NULL;
		$this->maximumWidth = $maximumWidth ? (integer)$maximumWidth : NULL;
		$this->height = $height ? (integer)$height : NULL;
		$this->maximumHeight = $maximumHeight ? (integer)$maximumHeight : NULL;
		$this->allowCropping = $allowCropping ? (boolean)$allowCropping : FALSE;
		$this->allowUpScaling = $allowUpScaling ? (boolean)$allowUpScaling : FALSE;
	}

	/**
	 * @return integer
	 */
	public function getWidth() {
		if ($this->width !== NULL) {
			return $this->width;
		}
		// @deprecated since 2.0, simulate the behaviour of 1.2
		if ($this->height === NULL && $this->isCroppingAllowed() && $this->getMaximumWidth() !== NULL && $this->getMaximumHeight() !== NULL) {
			return $this->getMaximumWidth();
		}
		return NULL;
	}

	/**
	 * @return integer
	 */
	public function getMaximumWidth() {
		return $this->maximumWidth;
	}

	/**
	 * @return integer
	 */
	public function getHeight() {
		if ($this->height !== NULL) {
			return $this->height;
		}
		// @deprecated since 2.0, simulate the behaviour of 1.2
		if ($this->width === NULL && $this->isCroppingAllowed() && $this->getMaximumWidth() !== NULL && $this->getMaximumHeight() !== NULL) {
			return $this->getMaximumHeight();
		}
		return NULL;
	}

	/**
	 * @return integer
	 */
	public function getMaximumHeight() {
		return $this->maximumHeight;
	}

	/**
	 * @return boolean
	 */
	public function getRatioMode() {
		return ($this->isCroppingAllowed() ? ImageInterface::RATIOMODE_OUTBOUND : ImageInterface::RATIOMODE_INSET);
	}

	/**
	 * @return boolean
	 */
	public function isCroppingAllowed() {
		return $this->allowCropping;
	}

	/**
	 * @return boolean
	 */
	public function isUpScalingAllowed() {
		return $this->allowUpScaling;
	}

	/**
	 * @return string
	 */
	public function getHash() {
		return md5(json_encode($this->toArray()));
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$data = array_filter([
			'width' => $this->getWidth(),
			'maximumWidth' => $this->getMaximumWidth(),
			'height' => $this->getHeight(),
			'maximumHeight' => $this->getMaximumHeight(),
			'ratioMode' => $this->getRatioMode(),
			'allowUpScaling' => $this->isUpScalingAllowed()
		], function($value) {
			return $value !== NULL;
		});
		ksort($data);
		return $data;
	}

}
