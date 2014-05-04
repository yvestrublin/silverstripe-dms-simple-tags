<?php

class DMSTagExtension extends DataExtension {
	
	public static $undefinedValue = '#undefinedValue#';

	public function validate(ValidationResult $validationResult) {
		if(!$this->owner->Category)
			$validationResult->combineAnd(new ValidationResult(false, _t('DMSTagExtension.NoCategory', 'You must at least enter a category.')));
		else {
			$this->owner->Category = trim($this->owner->Category);
			
			// this is done to prevent the method 'removeTag' in 'DMSDocument' to erase all the tags with same category if no value is defined on the removing Tag
			if(!$this->owner->Value) $this->owner->Value = self::$undefinedValue;
			else $this->owner->Value = trim($this->owner->Value);
			
			$query = "SELECT COUNT(*) FROM \"DMSTag\" WHERE \"DMSTag\".\"Category\" LIKE '{$this->owner->Category}' AND \"DMSTag\".\"Value\" LIKE '{$this->owner->Value}'";

			if(DB::query($query)->value()) 
				$validationResult->combineAnd(new ValidationResult(false, _t('DMSTagExtension.CategoryValueNotUnique', 'The category and value you try to create already exists.')));

		}
	}
}