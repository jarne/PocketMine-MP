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

/**
 * API for Minecraft: Bedrock custom UI (forms)
 */
namespace pocketmine\form;

use pocketmine\Player;

/**
 * Base class for a custom form. Forms are serialized to JSON data to be sent to clients.
 */
abstract class Form implements \JsonSerializable{

	const TYPE_MODAL = "modal";
	const TYPE_MENU = "form";
	const TYPE_CUSTOM_FORM = "custom_form";

	/** @var string */
	protected $title = "";
	/** @var bool */
	private $queued = false;

	public function __construct(string $title){
		$this->title = $title;
	}

	/**
	 * Returns the type used to show this form to clients
	 * @return string
	 */
	abstract public function getType() : string;

	/**
	 * Returns the text shown on the form title-bar.
	 * @return string
	 */
	public function getTitle() : string{
		return $this->title;
	}

	/**
	 * Handles a form response from a player. Plugins should not override this method, override {@link onSubmit}
	 * instead.
	 *
	 * @param Player $player
	 * @param mixed  $data
	 *
	 * @return Form|null a form which will be opened immediately (before queued forms) as a response to this form, or null if not applicable.
	 */
	abstract public function handleResponse(Player $player, $data) : ?Form;

	/**
	 * Called when a player submits this form. Each form type usually has its own methods for getting relevant data from
	 * them.
	 *
	 * Plugins should extend the class and override this function and add their own code to handle form responses as
	 * they wish.
	 *
	 * @param Player $player
	 * @return Form|null a form which will be opened immediately (before queued forms) as a response to this form, or null if not applicable.
	 */
	abstract public function onSubmit(Player $player) : ?Form;

	/**
	 * Returns whether the form has already been sent to a player or not. Note that you cannot send the form again if
	 * this is true.
	 *
	 * @return bool
	 */
	public function hasBeenQueued() : bool{
		return $this->queued;
	}

	/**
	 * Called to flag the form as having been sent to prevent it being used again, to avoid concurrency issues.
	 */
	public function setHasBeenQueued() : void{
		$this->queued = true;
	}

	/**
	 * Clears response data from a form, useful if you want to reuse the same form object several times.
	 */
	public function clearResponseData() : void{

	}

	/**
	 * Serializes the form to JSON for sending to clients.
	 *
	 * @return array
	 */
	final public function jsonSerialize() : array{
		$jsonBase = [
			"type" => $this->getType(),
			"title" => $this->getTitle()
		];

		return array_merge($jsonBase, $this->serializeFormData());
	}

	/**
	 * Serializes additional data needed to show this form to clients.
	 * @return array
	 */
	abstract protected function serializeFormData() : array;

}
