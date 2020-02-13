<?php

declare(strict_types=1);

namespace Kubomikita\Tracy;

use Kubomikita\Caching\Storages\SQLiteJournal;
use Nette;
use Tracy;


/**
 * Debug panel for Nette\Database.
 */
class CacheJournalPanel implements Tracy\IBarPanel {
	use Nette\SmartObject;

	/** @var Nette\Caching\Storages\IJournal */
	private $journal;
	/** @var array */
	private $tags;

	public function __construct(Nette\Caching\Storages\IJournal $journal) {
		$this->journal = $journal;
		if(!($this->journal instanceof SQLiteJournal)){
			throw new Nette\InvalidArgumentException("This '".get_class($this->journal)."' journal not support 'get' method.");
		}
		$this->tags = $this->journal->getTags();
	}


	public function getPanel() {
		$tags = $this->tags;
		ob_start(function () {});
		require __DIR__ . '/CacheJournalPanel.panel.phtml';
		return ob_get_clean();
	}

	public function getTab() {
		$icon = '<svg width="24" height="24" viewBox="0 0 24 24" class="icon icon-alarm" xmlns="http://www.w3.org/2000/svg">
    <path id="icon-alarm" d="M11.5,22C11.64,22 11.77,22 11.9,21.96C12.55,21.82 13.09,21.38 13.34,20.78C13.44,20.54 13.5,20.27 13.5,20H9.5A2,2 0 0,0 11.5,22M18,10.5C18,7.43 15.86,4.86 13,4.18V3.5A1.5,1.5 0 0,0 11.5,2A1.5,1.5 0 0,0 10,3.5V4.18C7.13,4.86 5,7.43 5,10.5V16L3,18V19H20V18L18,16M19.97,10H21.97C21.82,6.79 20.24,3.97 17.85,2.15L16.42,3.58C18.46,5 19.82,7.35 19.97,10M6.58,3.58L5.15,2.15C2.76,3.97 1.18,6.79 1,10H3C3.18,7.35 4.54,5 6.58,3.58Z"></path>
  </svg>';
		return $icon . " (". count($this->tags)." tags)";
	}

}