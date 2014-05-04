<?php
class DMSDocumentExtension extends DataExtension {
	
	public function updateCMSFields(FieldList $fields) {
		
		if($this->owner->ID) {
			$srcTags = function(){
				$tags = array();
				foreach (DMSTag::get() as $t)
					$tags[$t->ID] = $t->Category . ($t->Value != DMSTagExtension::$undefinedValue ? ' -> ' . $t->Value : '');
				return $tags;
			};

			$selectTags = ListboxField::create(
					'DocumentTags',
					_t('DMSDocumentExtension.Tags', 'Tags'),
					$srcTags(),
					implode(',', $this->owner->Tags()->column()),
					null,
					true
				)->useAddNew('DMSTag', $srcTags, FieldList::create(
					TextField::create('Category', _t('DMSDocumentExtension.Category', 'Category *')),
					TextField::create('Value', _t('DMSDocumentExtension.Value', 'Value')),
					HiddenField::create('MultiValue', null, 1)
			));

			$fields->insertAfter($selectTags, 'Description');
		}

	}

	public function onBeforeWrite() {
		$changedFields = $this->owner->getChangedFields(false, 1);

		if(array_key_exists("DocumentTags", $changedFields)) {
			$currentTags = explode(',', $this->owner->getField('DocumentTags'));
			$oldTags = DMSTag::get()
				->innerJoin("DMSDocument_Tags", "\"DMSDocument_Tags\".\"DMSTagID\" = \"DMSTag\".\"ID\" AND \"DMSDocument_Tags\".\"DMSDocumentID\" = " . $this->owner->ID)->column();
			
			// delete the tags
			foreach(array_diff($oldTags, $currentTags) as $idTag){
				$tag = DMSTag::get()->byID($idTag);
				if($tag) $this->owner->removeTag($tag->Category, $tag->Value ? $tag->Value : NULL);
			}
			// add the tags
			foreach(array_diff($currentTags, $oldTags) as $idTag){
				$tag = DMSTag::get()->byID($idTag);
				if($tag) $tag->Documents()->add($this->owner);
			}
		}
	}

	public function onBeforeDelete() {
		$this->owner->removeAllTags();
	}
}