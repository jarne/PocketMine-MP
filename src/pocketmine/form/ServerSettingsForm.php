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

/**
 * Represents a custom form which can be shown in the Settings menu on the client. This is exactly the same as a regular
 * CustomForm, except that this type can also have an icon which can be shown on the settings section button.
 */
abstract class ServerSettingsForm extends CustomForm{
	/**
	 * @var FormIcon|null
	 */
	private $icon;

	public function __construct(string $title, ?FormIcon $icon, CustomFormElement ...$elements){
		parent::__construct($title, ...$elements);
		$this->icon = $icon;
	}

	public function hasIcon() : bool{
		return $this->icon !== null;
	}

	public function getIcon() : ?FormIcon{
		return $this->icon;
	}

	public function serializeFormData() : array{
		$data = parent::serializeFormData();

		if($this->hasIcon()){
			$data["icon"] = $this->icon;
		}

		return $data;
	}

}