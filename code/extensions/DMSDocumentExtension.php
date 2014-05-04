<?php
class DMSDocumentExtension extends DataExtension {
	
	public function updateCMSFields(FieldList $fields) {
		
		if($this->owner->ID) {
			// Requirements::css('app/css/quickaddnewAdmin.css');

			$srcTags = function(){
				return DMSTag::get()->map('ID', 'Category')->toArray();
			};

			$selectTags = ListboxField::create(
					'DocumentTags',
					_t('DMSDocumentExtension.Tags', 'Tags'),
					$srcTags(),
					implode(',', $this->owner->Tags()->column()),
					null,
					true
				)->useAddNew('DMSTag', $srcTags, FieldList::create(
					AjaxUniqueTextField::create(
						'Category',
						_t('DMSDocumentExtension.Category', 'Category'),
						'Category',
						'DMSTag'
					),
					HiddenField::create('MultiValue', null, 0)
			));

			$fields->insertAfter($selectTags, 'Description');
		}

	}

	public function onBeforeWrite() {
		$changedFields = $this->owner->getChangedFields(false, 1);
		if(array_key_exists("DocumentTags", $changedFields)) {
			$currentTags = explode(',', $this->owner->getField('DocumentTags'));
			$oldTags = DMSTag::get()->innerJoin("DMSDocument_Tags", "\"DMSDocument_Tags\".\"DMSTagID\" = \"DMSTag\".\"ID\" AND \"DMSDocument_Tags\".\"DMSDocumentID\" = " . $this->owner->ID)->column();
			
			// delete the tags
			foreach(array_diff($oldTags, $currentTags) as $idTag){
				$tag = DMSTag::get()->byID($idTag);
				if($tag) $this->owner->removeTag($tag->Category);
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