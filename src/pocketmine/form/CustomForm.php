<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\form;

use pocketmine\form\element\CustomFormElement;
use pocketmine\Player;

abstract class CustomForm extends Form{

	/** @var CustomFormElement[] */
	private $elements;

	/**
	 * @param string              $title
	 * @param CustomFormElement[] ...$elements
	 */
	public function __construct(string $title, CustomFormElement ...$elements){
		parent::__construct($title);
		$this->elements = $elements;
	}

	/**
	 * @return string
	 */
	public function getType() : string{
		return Form::TYPE_CUSTOM_FORM;
	}

	/**
	 * @param int $index
	 *
	 * @return CustomFormElement|null
	 */
	public function getElement(int $index) : ?CustomFormElement{
		return $this->elements[$index] ?? null;
	}

	/**
	 * @return CustomFormElement[]
	 */
	public function getAllElements() : array{
		return $this->elements;
	}

	public function onSubmit(Player $player) : ?Form{
		return null;
	}

	/**
	 * Called when a player closes the form without submitting it.
	 * @param Player $player
	 * @return Form|null a form which will be opened immediately (before queued forms) as a response to this form, or null if not applicable.
	 */
	public function onClose(Player $player) : ?Form{
		return null;
	}


	public function handleResponse(Player $player, $data) : ?Form{
		if($data === null){
			return $this->onClose($player);
		}

		if(is_array($data)){
			/** @var array $data */
			foreach($data as $index => $value){
				$this->elements[$index]->setValue($value);
			}

			return $this->onSubmit($player);
		}

		throw new \UnexpectedValueException("Expected array or NULL, got " . gettype($data));
	}

	public function serializeFormData() : array{
		return [
			"content" => $this->elements
		];
	}
}
