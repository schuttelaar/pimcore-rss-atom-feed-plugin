<?php 

/**
 * This controller uses Zend_Feed_Writer to generate ATOM and RSS feeds with the published documents of a Pimcore website.
*/
class Feed_FeedController extends Website_Controller_Action {

	protected $defaultTitle;
	protected $baseUrl;
	protected $documentBody;
	protected $limit = 25;
	protected $description = '';
	protected $author = '';

	public function init() {
		parent::init();
		if($this->config->feedDefaultTitle) {
			$this->defaultTitle = $this->config->feedDefaultTitle;
		} else {
			throw new Exception("No website setting with key 'feedDefaultTitle' found.");
		}

		if($this->config->feedBaseUrl) {
			$this->baseUrl = $this->config->feedBaseUrl;
		} else {
			throw new Exception("No website setting with key 'feedBaseUrl' found.");
		}

		if($this->config->feedDocumentBody) {
			$this->documentBody = $this->config->feedDocumentBody;
		} else {
			throw new Exception("No website setting with key 'feedDocumentBody' found.");
		}

		if($this->config->feedLimit) {
			$this->limit = $this->config->feedLimit;
		} 

		if($this->config->feedDescription) {
			$this->description = $this->config->feedDescription;
		}

		if($this->config->feedAuthor) {
			$this->author = $this->config->feedAuthor;
		} else {
			$this->author = 'unknown';
		}
	}

	/**
	 * Get most recently created documents (with type "page")
	 * @param int $limit Maximum number of documents to return. Default is 25.
	 * @return object Document_List
	*/
	protected function getNewPages($limit = 25) {

		$list = new Document_List();
		$list->setLimit($limit);
		$list->setOrderKey('creationDate');
		$list->setOrder('desc');
		$list->setCondition("type='page'");
		return $list->load();
	}

	/**
	 * Create an Atom feed.
	*/
	public function atomAction() {

		$documents = $this->getNewPages($this->limit);

		$feed = new Zend_Feed_Writer_Feed;
		$feed->setTitle($this->defaultTitle);
		$feed->setLink($this->baseUrl.'/');
		$feed->setFeedLink($this->baseUrl.$_SERVER['REQUEST_URI'], 'atom');
		$feed->setId($this->baseUrl);
		$feed->addAuthor(array('name' => $this->author));

		$modDate = 0;

		foreach($documents as $document) {

			if($document->hasProperty('showInFeed') && !$document->getProperty('showInFeed')) {
				continue;
			}

			if($document->getModificationDate() > $modDate) {
				$modDate = $document->getModificationDate();
			}

			$content = trim(str_replace('&nbsp;', ' ', $document->elements[$this->documentBody]->text));
			$descr = $document->getDescription();
			$title = $document->title;
			if(empty($title)) {
				$title = $this->defaultTitle;
			}

			$entry = $feed->createEntry();
			$entry->setTitle($title);
			$entry->setLink($this->baseUrl.$document->getFullPath());
			$entry->setDateModified($document->getModificationDate());
			$entry->setDateCreated($document->getCreationDate());
			if(!empty($descr)) {
				$entry->setDescription($descr);
			}
			if(!empty($content)) {
				$entry->setContent($content);
			}
			$feed->addEntry($entry);
		}

		$feed->setDateModified($modDate);

		$this->getResponse()->setHeader('Content-Type', 'application/atom+xml; charset=utf-8');
		echo $feed->export('atom');
		exit();
	}

	/**
	 * Create an RSS feed.
	*/
	public function rssAction() {

		$documents = $this->getNewPages($limit);

		$description = $this->description;

		$feed = new Zend_Feed_Writer_Feed;
		$feed->setTitle($this->defaultTitle);
		if(empty($this->description)) {
			$description = $this->defaultTitle;
		}
		$feed->setDescription($description);
		$feed->setLink($this->baseUrl.'/');
		$feed->setFeedLink($this->baseUrl.$_SERVER['REQUEST_URI'], 'rss');
		$feed->setId($this->baseUrl);
		$feed->addAuthor($this->author);

		$modDate = 0;

		foreach($documents as $document) {

			if($document->hasProperty('showInFeed') && !$document->getProperty('showInFeed')) {
				continue;
			}

			if($document->getModificationDate() > $modDate) {
				$modDate = $document->getModificationDate();
			}

			$content = trim(str_replace('&nbsp;', ' ', $document->elements[$this->documentBody]->text));
			$descr = $document->getDescription();
			$title = $document->title;
			if(empty($title)) {
				$title = $this->defaultTitle;
			}

			$entry = $feed->createEntry();
			$entry->setTitle($title);
			$entry->setLink($this->baseUrl.$document->getFullPath());
			$entry->setDateModified($document->getModificationDate());
			$entry->setDateCreated($document->getCreationDate());
			if(!empty($descr)) {
				$entry->setDescription($descr);
			}
			if(!empty($content)) {
				$entry->setContent($content);
			}
			$feed->addEntry($entry);
		}

		$feed->setDateModified($modDate);

		$this->getResponse()->setHeader('Content-Type', 'application/rss+xml; charset=utf-8');
		echo $feed->export('rss');
		exit();
	}

}