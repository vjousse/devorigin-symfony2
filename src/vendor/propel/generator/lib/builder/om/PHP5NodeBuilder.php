<?php

/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

require_once 'builder/om/ObjectBuilder.php';

/**
 * Generates a PHP5 tree node Object class for user object model (OM).
 *
 * This class produces the base tree node object class (e.g. BaseMyTable) which contains all
 * the custom-built accessor and setter methods.
 *
 * This class replaces the Node.tpl, with the intent of being easier for users
 * to customize (through extending & overriding).
 *
 * @author     Hans Lellelid <hans@xmpl.org>
 * @package    propel.generator.builder.om
 */
class PHP5NodeBuilder extends ObjectBuilder
{

	/**
	 * Gets the package for the [base] object classes.
	 * @return     string
	 */
	public function getPackage()
	{
		return parent::getPackage() . ".om";
	}

	/**
	 * Returns the name of the current class being built.
	 * @return     string
	 */
	public function getUnprefixedClassname()
	{
		return $this->getBuildProperty('basePrefix') . $this->getStubNodeBuilder()->getUnprefixedClassname();
	}

	/**
	 * Adds the include() statements for files that this class depends on or utilizes.
	 * @param      string &$script The script will be modified in this method.
	 */
	protected function addIncludes(&$script)
	{
	} // addIncludes()

	/**
	 * Adds class phpdoc comment and openning of class.
	 * @param      string &$script The script will be modified in this method.
	 */
	protected function addClassOpen(&$script)
	{

		$table = $this->getTable();
		$tableName = $table->getName();
		$tableDesc = $table->getDescription();

		$script .= "
/**
 * Base class that represents a row from the '$tableName' table.
 *
 * $tableDesc
 *";
		if ($this->getBuildProperty('addTimeStamp')) {
			$now = strftime('%c');
			$script .= "
 * This class was autogenerated by Propel " . $this->getBuildProperty('version') . " on:
 *
 * $now
 *";
		}
		$script .= "
 * @package    propel.generator.".$this->getPackage()."
 */
abstract class ".$this->getClassname()." implements IteratorAggregate {
";
	}

	/**
	 * Specifies the methods that are added as part of the basic OM class.
	 * This can be overridden by subclasses that wish to add more methods.
	 * @see        ObjectBuilder::addClassBody()
	 */
	protected function addClassBody(&$script)
	{
		$table = $this->getTable();

		$this->addAttributes($script);

		$this->addConstructor($script);

		$this->addCallOverload($script);
		$this->addSetIteratorOptions($script);
		$this->addGetIterator($script);

		$this->addGetNodeObj($script);
		$this->addGetNodePath($script);
		$this->addGetNodeIndex($script);
		$this->addGetNodeLevel($script);

		$this->addHasChildNode($script);
		$this->addGetChildNodeAt($script);
		$this->addGetFirstChildNode($script);
		$this->addGetLastChildNode($script);
		$this->addGetSiblingNode($script);

		$this->addGetParentNode($script);
		$this->addGetAncestors($script);
		$this->addIsRootNode($script);

		$this->addSetNew($script);
		$this->addSetDeleted($script);
		$this->addAddChildNode($script);
		$this->addMoveChildNode($script);
		$this->addSave($script);

		$this->addDelete($script);
		$this->addEquals($script);

		$this->addAttachParentNode($script);
		$this->addAttachChildNode($script);
		$this->addDetachParentNode($script);
		$this->addDetachChildNode($script);
		$this->addShiftChildNodes($script);
		$this->addInsertNewChildNode($script);

		$this->addAdjustStatus($script);
		$this->addAdjustNodePath($script);

	}

	/**
	 * Closes class.
	 * @param      string &$script The script will be modified in this method.
	 */
	protected function addClassClose(&$script)
	{
		$script .= "
} // " . $this->getClassname() . "
";
	}


	/**
	 * Adds class attributes.
	 * @param      string &$script The script will be modified in this method.
	 */
	protected function addAttributes(&$script)
	{
		$script .= "
	/**
	 * @var        ".$this->getStubObjectBuilder()->getClassname()." object wrapped by this node.
	 */
	protected \$obj = null;

	/**
	 * The parent node for this node.
	 * @var        ".$this->getStubNodeBuilder()->getClassname()."
	 */
	protected \$parentNode = null;

	/**
	 * Array of child nodes for this node. Nodes indexes are one-based.
	 * @var        array
	 */
	protected \$childNodes = array();
";
	}

	/**
	 * Adds the constructor.
	 * @param      string &$script The script will be modified in this method.
	 */
	protected function addConstructor(&$script)
	{
		$script .= "
	/**
	 * Constructor.
	 *
	 * @param      ".$this->getStubObjectBuilder()->getClassname()." \$obj Object wrapped by this node.
	 */
	public function __construct(\$obj = null)
	{
		if (\$obj !== null) {
			\$this->obj = \$obj;
		} else {
			\$setNodePath = 'set' . ".$this->getStubNodePeerBuilder()->getClassname()."::NPATH_PHPNAME;
			\$this->obj = new ".$this->getStubObjectBuilder()->getClassname()."();
			\$this->obj->\$setNodePath('0');
		}
	}
";
	}



	protected function addCallOverload(&$script)
	{
		$script .= "
	/**
	 * Convenience overload for wrapped object methods.
	 *
	 * @param      string Method name to call on wrapped object.
	 * @param      mixed Parameter accepted by wrapped object set method.
	 * @return     mixed Return value of wrapped object method.
	 * @throws     PropelException Fails if method is not defined for wrapped object.
	 */
	public function __call(\$name, \$parms)
	{
		if (method_exists(\$this->obj, \$name))
			return call_user_func_array(array(\$this->obj, \$name), \$parms);
		else
			throw new PropelException('get method not defined: \$name');
	}
";
	}

	protected function addSetIteratorOptions(&$script)
	{
		$script .= "

	/**
	 * Sets the default options for iterators created from this object.
	 * The options are specified in map format. The following options
	 * are supported by all iterators. Some iterators may support other
	 * options:
	 *
	 *   \"querydb\" - True if nodes should be retrieved from database.
	 *   \"con\" - Connection to use if retrieving from database.
	 *
	 * @param      string Type of iterator to use (\"pre\", \"post\", \"level\").
	 * @param      array Map of option name => value.
	 * @return     void
	 * @todo       Implement other iterator types (i.e. post-order, level, etc.)
	 */
	public function setIteratorOptions(\$type, \$opts)
	{
		\$this->itType = \$type;
		\$this->itOpts = \$opts;
	}
";
	}

	protected function addGetIterator(&$script)
	{
		$script .= "
	/**
	 * Returns a pre-order iterator for this node and its children.
	 *
	 * @param      string Type of iterator to use (\"pre\", \"post\", \"level\")
	 * @param      array Map of option name => value.
	 * @return     NodeIterator
	 */
	public function getIterator(\$type = null, \$opts = null)
	{
		if (\$type === null)
			\$type = (isset(\$this->itType) ? \$this->itType : 'Pre');

		if (\$opts === null)
			\$opts = (isset(\$this->itOpts) ? \$this->itOpts : array());

		\$itclass = ucfirst(strtolower(\$type)) . 'OrderNodeIterator';

    require_once('propel/om/' . \$itclass . '.php'); 
		return new \$itclass(\$this, \$opts); 
	}
";
	}

	protected function addGetNodeObj(&$script)
	{
		$script .= "
	/**
	 * Returns the object wrapped by this class.
	 * @return     ".$this->getStubObjectBuilder()->getClassname()."
	 */
	public function getNodeObj()
	{
		return \$this->obj;
	}
";
	}

	protected function addGetNodePath(&$script)
	{
		$script .= "
	/**
	 * Convenience method for retrieving nodepath.
	 * @return     string
	 */
	public function getNodePath()
	{
		\$getNodePath = 'get' . ".$this->getStubNodePeerBuilder()->getClassname()."::NPATH_PHPNAME;
		return \$this->obj->\$getNodePath();
	}
";
	}

	protected function addGetNodeIndex(&$script)
	{
		$script .= "
	/**
	 * Returns one-based node index among siblings.
	 * @return     int
	 */
	public function getNodeIndex()
	{
		\$npath =& \$this->getNodePath();
		\$sep = strrpos(\$npath, ".$this->getStubNodePeerBuilder()->getClassname()."::NPATH_SEP);
		return (int) (\$sep !== false ? substr(\$npath, \$sep+1) : \$npath);
	}
";
	}

	protected function addGetNodeLevel(&$script)
	{
		$script .= "
	/**
	 * Returns one-based node level within tree (root node is level 1).
	 * @return     int
	 */
	public function getNodeLevel()
	{
		return (substr_count(\$this->getNodePath(), ".$this->getStubNodePeerBuilder()->getClassname()."::NPATH_SEP) + 1);
	}
";
	}

	protected function addHasChildNode(&$script)
	{
		$script .= "
	/**
	 * Returns true if specified node is a child of this node. If recurse is
	 * true, checks if specified node is a descendant of this node.
	 *
	 * @param      ".$this->getStubNodeBuilder()->getClassname()." Node to look for.
	 * @param      boolean True if strict comparison should be used.
	 * @param      boolean True if all descendants should be checked.
	 * @return     boolean
	 */
	public function hasChildNode(\$node, \$strict = false, \$recurse = false)
	{
		foreach (\$this->childNodes as \$childNode)
		{
			if (\$childNode->equals(\$node, \$strict))
				return true;

			if (\$recurse && \$childNode->hasChildNode(\$node, \$recurse))
				return true;
		}

		return false;
	}
";
	}

	protected function addGetChildNodeAt(&$script)
	{
		$script .= "
	/**
	 * Returns child node at one-based index. Retrieves from database if not
	 * loaded yet.
	 *
	 * @param      int One-based child node index.
	 * @param      boolean True if child should be retrieved from database.
	 * @param      PropelPDO Connection to use if retrieving from database.
	 * @return     ".$this->getStubNodeBuilder()->getClassname()."
	 */
	public function getChildNodeAt(\$i, \$querydb = false, PropelPDO \$con = null)
	{
		if (\$querydb &&
			!\$this->obj->isNew() &&
			!\$this->obj->isDeleted() &&
			!isset(\$this->childNodes[\$i]))
		{
			\$criteria = new Criteria(".$this->getStubPeerBuilder()->getClassname()."::DATABASE_NAME);
			\$criteria->add(".$this->getStubNodePeerBuilder()->getClassname()."::NPATH_COLNAME, \$this->getNodePath() . ".$this->getStubNodePeerBuilder()->getClassname()."::NPATH_SEP . \$i, Criteria::EQUAL);

			if (\$childObj = ".$this->getStubPeerBuilder()->getClassname()."::doSelectOne(\$criteria, \$con))
				\$this->attachChildNode(new ".$this->getStubNodeBuilder()->getClassname()."(\$childObj));
		}

		return (isset(\$this->childNodes[\$i]) ? \$this->childNodes[\$i] : null);
	}
";
	}

	protected function addGetFirstChildNode(&$script)
	{
		$script .= "
	/**
	 * Returns first child node (if any). Retrieves from database if not loaded yet.
	 *
	 * @param      boolean True if child should be retrieved from database.
	 * @param      PropelPDO Connection to use if retrieving from database.
	 * @return     ".$this->getStubNodeBuilder()->getClassname()."
	 */
	public function getFirstChildNode(\$querydb = false, PropelPDO \$con = null)
	{
		return \$this->getChildNodeAt(1, \$querydb, \$con);
	}
";
	}

	protected function addGetLastChildNode(&$script)
	{
		$peerClassname = $this->getStubPeerBuilder()->getClassname();
		$nodePeerClassname = $this->getStubNodePeerBuilder()->getClassname();

		$script .= "
	/**
	 * Returns last child node (if any).
	 *
	 * @param      boolean True if child should be retrieved from database.
	 * @param      PropelPDO Connection to use if retrieving from database.
	 */
	public function getLastChildNode(\$querydb = false, PropelPDO \$con = null)
	{
		\$lastNode = null;

		if (\$this->obj->isNew() || \$this->obj->isDeleted())
		{
			end(\$this->childNodes);
			\$lastNode = (count(\$this->childNodes) ? current(\$this->childNodes) : null);
		}
		else if (\$querydb)
		{
			\$db = Propel::getDb($peerClassname::DATABASE_NAME);
			\$criteria = new Criteria($peerClassname::DATABASE_NAME);
			\$criteria->add($nodePeerClassname::NPATH_COLNAME, \$this->getNodePath() . $nodePeerClassname::NPATH_SEP . '%', Criteria::LIKE);
			\$criteria->addAnd($nodePeerClassname::NPATH_COLNAME, \$this->getNodePath() . $nodePeerClassname::NPATH_SEP . '%' . $nodePeerClassname::NPATH_SEP . '%', Criteria::NOT_LIKE);
			$peerClassname::addSelectColumns(\$criteria);
			\$criteria->addAsColumn('npathlen', \$db->strLength($nodePeerClassname::NPATH_COLNAME));
			\$criteria->addDescendingOrderByColumn('npathlen');
			\$criteria->addDescendingOrderByColumn($nodePeerClassname::NPATH_COLNAME);

			\$lastObj = $peerClassname::doSelectOne(\$criteria, \$con);

			if (\$lastObj !== null)
			{
				\$lastNode = new ".$this->getStubNodeBuilder()->getClassname()."(\$lastObj);

				end(\$this->childNodes);
				\$endNode = (count(\$this->childNodes) ? current(\$this->childNodes) : null);

				if (\$endNode)
				{
					if (\$endNode->getNodePath() > \$lastNode->getNodePath())
						throw new PropelException('Cached child node inconsistent with database.');
					else if (\$endNode->getNodePath() == \$lastNode->getNodePath())
						\$lastNode = \$endNode;
					else
						\$this->attachChildNode(\$lastNode);
				}
				else
				{
					\$this->attachChildNode(\$lastNode);
				}
			}
		}

		return \$lastNode;
	}
";
	}

	protected function addGetSiblingNode(&$script)
	{
		$script .= "
	/**
	 * Returns next (or previous) sibling node or null. Retrieves from database if
	 * not loaded yet.
	 *
	 * @param      boolean True if previous sibling should be returned.
	 * @param      boolean True if sibling should be retrieved from database.
	 * @param      PropelPDO Connection to use if retrieving from database.
	 * @return     ".$this->getStubNodeBuilder()->getClassname()."
	 */
	public function getSiblingNode(\$prev = false, \$querydb = false, PropelPDO \$con = null)
	{
		\$nidx = \$this->getNodeIndex();

		if (\$this->isRootNode())
		{
			return null;
		}
		else if (\$prev)
		{
			if (\$nidx > 1 && (\$parentNode = \$this->getParentNode(\$querydb, \$con)))
				return \$parentNode->getChildNodeAt(\$nidx-1, \$querydb, \$con);
			else
				return null;
		}
		else
		{
			if (\$parentNode = \$this->getParentNode(\$querydb, \$con))
				return \$parentNode->getChildNodeAt(\$nidx+1, \$querydb, \$con);
			else
				return null;
		}
	}
";
	}

	protected function addGetParentNode(&$script)
	{
		$peerClassname = $this->getStubPeerBuilder()->getClassname();
		$nodePeerClassname = $this->getStubNodePeerBuilder()->getClassname();

		$script .= "
	/**
	 * Returns parent node. Loads from database if not cached yet.
	 *
	 * @param      boolean True if parent should be retrieved from database.
	 * @param      PropelPDO Connection to use if retrieving from database.
	 * @return     ".$this->getStubNodeBuilder()->getClassname()."
	 */
	public function getParentNode(\$querydb = true, PropelPDO \$con = null)
	{
		if (\$querydb &&
			\$this->parentNode === null &&
			!\$this->isRootNode() &&
			!\$this->obj->isNew() &&
			!\$this->obj->isDeleted())
		{
			\$npath =& \$this->getNodePath();
			\$sep = strrpos(\$npath, $nodePeerClassname::NPATH_SEP);
			\$ppath = substr(\$npath, 0, \$sep);

			\$criteria = new Criteria($peerClassname::DATABASE_NAME);
			\$criteria->add($nodePeerClassname::NPATH_COLNAME, \$ppath, Criteria::EQUAL);

			if (\$parentObj = $peerClassname::doSelectOne(\$criteria, \$con))
			{
				\$parentNode = new ".$this->getStubNodeBuilder()->getClassname()."(\$parentObj);
				\$parentNode->attachChildNode(\$this);
			}
		}

		return \$this->parentNode;
	}
";
	}

	protected function addGetAncestors(&$script)
	{
		$script .= "
	/**
	 * Returns an array of all ancestor nodes, starting with the root node
	 * first.
	 *
	 * @param      boolean True if ancestors should be retrieved from database.
	 * @param      PropelPDO Connection to use if retrieving from database.
	 * @return     array
	 */
	public function getAncestors(\$querydb = false, PropelPDO \$con = null)
	{
		\$ancestors = array();
		\$parentNode = \$this;

		while (\$parentNode = \$parentNode->getParentNode(\$querydb, \$con))
			array_unshift(\$ancestors, \$parentNode);

		return \$ancestors;
	}
";
	}

	protected function addIsRootNode(&$script)
	{
		$script .= "
	/**
	 * Returns true if node is the root node of the tree.
	 * @return     boolean
	 */
	public function isRootNode()
	{
		return (\$this->getNodePath() === '1');
	}
";
	}

	protected function addSetNew(&$script)
	{
		$script .= "
	/**
	 * Changes the state of the object and its descendants to 'new'.
	 * Also changes the node path to '0' to indicate that it is not a
	 * stored node.
	 *
	 * @param      boolean
	 * @return     void
	 */
	public function setNew(\$b)
	{
		\$this->adjustStatus('new', \$b);
		\$this->adjustNodePath(\$this->getNodePath(), '0');
	}
";
	}

	protected function addSetDeleted(&$script)
	{
		$script .= "
	/**
	 * Changes the state of the object and its descendants to 'deleted'.
	 *
	 * @param      boolean
	 * @return     void
	 */
	public function setDeleted(\$b)
	{
		\$this->adjustStatus('deleted', \$b);
	}
";
	}

	protected function addAddChildNode(&$script)
	{
		$peerClassname = $this->getStubPeerBuilder()->getClassname();
		$nodePeerClassname = $this->getStubNodePeerBuilder()->getClassname();

		$script .= "
	/**
	 * Adds the specified node (and its children) as a child to this node. If a
	 * valid \$beforeNode is specified, the node will be inserted in front of
	 * \$beforeNode. If \$beforeNode is not specified the node will be appended to
	 * the end of the child nodes.
	 *
	 * @param      ".$this->getStubNodeBuilder()->getClassname()." Node to add.
	 * @param      ".$this->getStubNodeBuilder()->getClassname()." Node to insert before.
	 * @param      PropelPDO Connection to use.
	 */
	public function addChildNode(\$node, \$beforeNode = null, PropelPDO \$con = null)
	{
		if (\$this->obj->isNew() && !\$node->obj->isNew())
			throw new PropelException('Cannot add stored nodes to a new node.');

		if (\$this->obj->isDeleted() || \$node->obj->isDeleted())
			throw new PropelException('Cannot add children in a deleted state.');

		if (\$this->hasChildNode(\$node))
			throw new PropelException('Node is already a child of this node.');

		if (\$beforeNode && !\$this->hasChildNode(\$beforeNode))
			throw new PropelException('Invalid beforeNode.');

		if (\$con === null)
			\$con = Propel::getConnection($peerClassname::DATABASE_NAME, Propel::CONNECTION_WRITE);
		
		if (!\$this->obj->isNew()) \$con->beginTransaction();
		
		try {	
			if (\$beforeNode)
			{
				// Inserting before a node.
				\$childIdx = \$beforeNode->getNodeIndex();
				\$this->shiftChildNodes(1, \$beforeNode->getNodeIndex(), \$con);
			}
			else
			{
				// Appending child node.
				if (\$lastNode = \$this->getLastChildNode(true, \$con))
					\$childIdx = \$lastNode->getNodeIndex()+1;
				else
					\$childIdx = 1;
			}

			// Add the child (and its children) at the specified index.

			if (!\$this->obj->isNew() && \$node->obj->isNew())
			{
				\$this->insertNewChildNode(\$node, \$childIdx, \$con);
			}
			else
			{
				// \$this->isNew() && \$node->isNew() ||
				// !\$this->isNew() && !node->isNew()

				\$srcPath = \$node->getNodePath();
				\$dstPath = \$this->getNodePath() . $nodePeerClassname::NPATH_SEP . \$childIdx;

				if (!\$node->obj->isNew())
				{
					$nodePeerClassname::moveNodeSubTree(\$srcPath, \$dstPath, \$con);
					\$parentNode = \$node->getParentNode(true, \$con);
				}
				else
				{
					\$parentNode = \$node->getParentNode();
				}

				if (\$parentNode)
				{
					\$parentNode->detachChildNode(\$node);
					\$parentNode->shiftChildNodes(-1, \$node->getNodeIndex()+1, \$con);
				}

				\$node->adjustNodePath(\$srcPath, \$dstPath);
			}

			if (!\$this->obj->isNew()) \$con->commit();

			\$this->attachChildNode(\$node);

		} catch (SQLException \$e) {
			if (!\$this->obj->isNew()) \$con->rollBack();
			throw new PropelException(\$e);
		}
	}
";
	}

	protected function addMoveChildNode(&$script)
	{
		$script .= "
	/**
	 * Moves the specified child node in the specified direction.
	 *
	 * @param      ".$this->getStubNodeBuilder()->getClassname()." Node to move.
	 * @param      int Number of spaces to move among siblings (may be negative).
	 * @param      PropelPDO Connection to use.
	 * @throws     PropelException
	 */
	public function moveChildNode(\$node, \$direction, PropelPDO \$con = null)
	{
		throw new PropelException('moveChildNode() not implemented yet.');
	}
";
	}


	protected function addSave(&$script)
	{

		$nodePeerClassname = $this->getStubNodePeerBuilder()->getClassname();
		$script .= "
	/**
	 * Saves modified object data to the datastore.
	 *
	 * @param      boolean If true, descendants will be saved as well.
	 * @param      PropelPDO Connection to use.
	 */
	public function save(\$recurse = false, PropelPDO \$con = null)
	{
		if (\$this->obj->isDeleted())
			throw new PropelException('Cannot save deleted node.');

		if (substr(\$this->getNodePath(), 0, 1) == '0')
			throw new PropelException('Cannot save unattached node.');

		if (\$this->obj->isColumnModified($nodePeerClassname::NPATH_COLNAME))
			throw new PropelException('Cannot save manually modified node path.');

		\$this->obj->save(\$con);

		if (\$recurse)
		{
			foreach (\$this->childNodes as \$childNode)
				\$childNode->save(\$recurse, \$con);
		}
	}
";
	}


	protected function addDelete(&$script)
	{
		$peerClassname = $this->getStubPeerBuilder()->getClassname();
		$nodePeerClassname = $this->getStubNodePeerBuilder()->getClassname();
		$script .= "
	/**
	 * Removes this object and all descendants from datastore.
	 *
	 * @param      PropelPDO Connection to use.
	 * @return     void
	 * @throws     PropelException
	 */
	public function delete(PropelPDO \$con = null)
	{
		if (\$this->obj->isDeleted()) {
			throw new PropelException('This node has already been deleted.');
		}

		if (\$con === null) {
			\$con = Propel::getConnection($peerClassname::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
			
		if (!\$this->obj->isNew()) {
			$nodePeerClassname::deleteNodeSubTree(\$this->getNodePath(), \$con);
		}

		if (\$parentNode = \$this->getParentNode(true, \$con)) {
			\$parentNode->detachChildNode(\$this);
			\$parentNode->shiftChildNodes(-1, \$this->getNodeIndex()+1, \$con);
		}

		\$this->setDeleted(true);
	}
";
	}

	protected function addEquals(&$script)
	{
		$nodeClassname = $this->getStubNodeBuilder()->getClassname();
		$script .= "
	/**
	 * Compares the object wrapped by this node with that of another node. Use
	 * this instead of equality operators to prevent recursive dependency
	 * errors.
	 *
	 * @param      $nodeClassname Node to compare.
	 * @param      boolean True if strict comparison should be used.
	 * @return     boolean
	 */
	public function equals(\$node, \$strict = false)
	{
		if (\$strict) {
			return (\$this->obj === \$node->obj);
		} else {
			return (\$this->obj == \$node->obj);
		}
	}
";
	}

	protected function addAttachParentNode(&$script)
	{
		$nodeClassname = $this->getStubNodeBuilder()->getClassname();
		$script .= "
	/**
	 * This method is used internally when constructing the tree structure
	 * from the database. To set the parent of a node, you should call
	 * addChildNode() on the parent.
	 *
	 * @param      $nodeClassname Parent node to attach.
	 * @return     void
	 * @throws     PropelException
	 */
	public function attachParentNode(\$node)
	{
		if (!\$node->hasChildNode(\$this, true))
			throw new PropelException('Failed to attach parent node for non-child.');

		\$this->parentNode = \$node;
	}
";
	}


	protected function addAttachChildNode(&$script)
	{
		$nodeClassname = $this->getStubNodeBuilder()->getClassname();
		$nodePeerClassname = $this->getStubNodePeerBuilder()->getClassname();
		$script .= "
	/**
	 * This method is used internally when constructing the tree structure
	 * from the database. To add a child to a node you should call the
	 * addChildNode() method instead.
	 *
	 * @param      $nodeClassname Child node to attach.
	 * @return     void
	 * @throws     PropelException
	 */
	public function attachChildNode(\$node)
	{
		if (\$this->hasChildNode(\$node))
			throw new PropelException('Failed to attach child node. Node already exists.');

		if (\$this->obj->isDeleted() || \$node->obj->isDeleted())
			throw new PropelException('Failed to attach node in deleted state.');

		if (\$this->obj->isNew() && !\$node->obj->isNew())
			throw new PropelException('Failed to attach non-new child to new node.');

		if (!\$this->obj->isNew() && \$node->obj->isNew())
			throw new PropelException('Failed to attach new child to non-new node.');

		if (\$this->getNodePath() . $nodePeerClassname::NPATH_SEP . \$node->getNodeIndex() != \$node->getNodePath())
			throw new PropelException('Failed to attach child node. Node path mismatch.');

		\$this->childNodes[\$node->getNodeIndex()] = \$node;
		ksort(\$this->childNodes);

		\$node->attachParentNode(\$this);
	}
";
	}

	protected function addDetachParentNode(&$script)
	{
		$nodeClassname = $this->getStubNodeBuilder()->getClassname();
		$script .= "
	/**
	 * This method is used internally when deleting nodes. It is used to break
	 * the link to this node's parent.
	 * @param      $nodeClassname Parent node to detach from.
	 * @return     void
	 * @throws     PropelException
	 */
	public function detachParentNode(\$node)
	{
		if (!\$node->hasChildNode(\$this, true))
			throw new PropelException('Failed to detach parent node from non-child.');

		unset(\$node->childNodes[\$this->getNodeIndex()]);
		\$this->parentNode = null;
	}
";
	}

	protected function addDetachChildNode(&$script)
	{
		$script .= "
	/**
	 * This method is used internally when deleting nodes. It is used to break
	 * the link to this between this node and the specified child.
	 * @param      ".$this->getStubNodeBuilder()->getClassname()." Child node to detach.
	 * @return     void
	 * @throws     PropelException
	 */
	public function detachChildNode(\$node)
	{
		if (!\$this->hasChildNode(\$node, true))
			throw new PropelException('Failed to detach non-existent child node.');

		unset(\$this->childNodes[\$node->getNodeIndex()]);
		\$node->parentNode = null;
	}
";
	}

	protected function addShiftChildNodes(&$script)
	{
		$peerClassname = $this->getStubPeerBuilder()->getClassname();
		$nodePeerClassname = $this->getStubNodePeerBuilder()->getClassname();

		$script .= "
	/**
	 * Shifts child nodes in the specified direction and offset index. This
	 * method assumes that there is already space available in the
	 * direction/offset indicated.
	 *
	 * @param      int Direction/# spaces to shift. 1=leftshift, 1=rightshift
	 * @param      int Node index to start shift at.
	 * @param      PropelPDO The connection to be used.
	 * @return     void
	 * @throws     PropelException
	 */
	protected function shiftChildNodes(\$direction, \$offsetIdx, PropelPDO \$con)
	{
		if (\$this->obj->isDeleted())
			throw new PropelException('Cannot shift nodes for deleted object');

		\$lastNode = \$this->getLastChildNode(true, \$con);
		\$lastIdx = (\$lastNode !== null ? \$lastNode->getNodeIndex() : 0);

		if (\$lastNode === null || \$offsetIdx > \$lastIdx)
			return;

		if (\$con === null)
			\$con = Propel::getConnection($peerClassname::DATABASE_NAME);

		if (!\$this->obj->isNew())
		{
			// Shift nodes in database.
			
			\$con->beginTransaction();
			
			try {
				\$n = \$lastIdx - \$offsetIdx + 1;
				\$i = \$direction < 1 ? \$offsetIdx : \$lastIdx;

				while (\$n--)
				{
					\$srcPath = \$this->getNodePath() . $nodePeerClassname::NPATH_SEP . \$i;			  // 1.2.2
					\$dstPath = \$this->getNodePath() . $nodePeerClassname::NPATH_SEP . (\$i+\$direction); // 1.2.3

					$nodePeerClassname::moveNodeSubTree(\$srcPath, \$dstPath, \$con);

					\$i -= \$direction;
				}

				\$con->commit();

			} catch (SQLException \$e) {
				\$con->rollBack();
				throw new PropelException(\$e);
			}
		}

		// Shift the in-memory objects.

		\$n = \$lastIdx - \$offsetIdx + 1;
		\$i = \$direction < 1 ? \$offsetIdx : \$lastIdx;

		while (\$n--)
		{
			if (isset(\$this->childNodes[\$i]))
			{
				\$srcPath = \$this->getNodePath() . $nodePeerClassname::NPATH_SEP . \$i;			  // 1.2.2
				\$dstPath = \$this->getNodePath() . $nodePeerClassname::NPATH_SEP . (\$i+\$direction); // 1.2.3

				\$this->childNodes[\$i+\$direction] = \$this->childNodes[\$i];
				\$this->childNodes[\$i+\$direction]->adjustNodePath(\$srcPath, \$dstPath);

				unset(\$this->childNodes[\$i]);
			}

			\$i -= \$direction;
		}

		ksort(\$this->childNodes);
	}
";
	}

	protected function addInsertNewChildNode(&$script)
	{
		$peerClassname = $this->getStubPeerBuilder()->getClassname();
		$nodePeerClassname = $this->getStubNodePeerBuilder()->getClassname();
		$nodeClassname = $this->getStubNodePeerBuilder()->getClassname();

		$script .= "
	/**
	 * Inserts the node and its children at the specified childIdx.
	 *
	 * @param      $nodeClassname Node to insert.
	 * @param      int One-based child index to insert at.
	 * @param      PropelPDO Connection to use.
	 * @param      void
	 */
	protected function insertNewChildNode(\$node, \$childIdx, PropelPDO \$con)
	{
		if (!\$node->obj->isNew())
			throw new PropelException('Failed to insert non-new node.');

		\$setNodePath = 'set' . $nodePeerClassname::NPATH_PHPNAME;

		\$node->obj->\$setNodePath(\$this->getNodePath() . $nodePeerClassname::NPATH_SEP . \$childIdx);
		\$node->obj->save(\$con);

		\$i = 1;
		foreach (\$node->childNodes as \$childNode)
			\$node->insertNewChildNode(\$childNode, \$i++, \$con);
	}
";
	}

	protected function addAdjustStatus(&$script)
	{
		$script .= "
	/**
	 * Adjust new/deleted status of node and all children.
	 *
	 * @param      string Status to change ('New' or 'Deleted')
	 * @param      boolean Value for status.
	 * @return     void
	 */
	protected function adjustStatus(\$status, \$b)
	{
		\$setStatus = 'set' . \$status;

		\$this->obj->\$setStatus(\$b);

		foreach (\$this->childNodes as \$childNode)
			\$childNode->obj->\$setStatus(\$b);
	}
";
	}

	protected function addAdjustNodePath(&$script)
	{
		$nodePeerClassname = $this->getStubNodePeerBuilder()->getClassname();
		$script .= "
	/**
	 * Adjust path of node and all children. This is used internally when
	 * inserting/moving nodes.
	 *
	 * @param      string Section of old path to change.
	 * @param      string New section to replace old path with.
	 * @return     void
	 */
	protected function adjustNodePath(\$oldBasePath, \$newBasePath)
	{
		\$setNodePath = 'set' . $nodePeerClassname::NPATH_PHPNAME;

		\$this->obj->\$setNodePath(\$newBasePath .  substr(\$this->getNodePath(), strlen(\$oldBasePath)));
		\$this->obj->resetModified($nodePeerClassname::NPATH_COLNAME);

		foreach (\$this->childNodes as \$childNode)
			\$childNode->adjustNodePath(\$oldBasePath, \$newBasePath);
	}
";
	}

} // PHP5NodeObjectBuilder
