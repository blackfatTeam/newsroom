<?php
use kartik\tree\TreeView;
use kartik\tree\Module;
use app\models\Category;

echo TreeView::widget([
		// single query fetch to render the tree
		'nodeView' => '@kvtree/views/_form',
		'query'             => Category::find()->addOrderBy('root, lft'),
		'headingOptions'    => ['label' => 'Categories'],
		'isAdmin'           => true,                       // optional (toggle to enable admin mode)
		'displayValue'      => 1,                           // initial display value
		'nodeAddlViews' => [Module::VIEW_PART_1 => '@app/components/views/treeAddView'],
		

]);

