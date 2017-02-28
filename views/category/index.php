<?php
use kartik\tree\TreeView;
use app\models\Category;

echo TreeView::widget([
		// single query fetch to render the tree
		'query'             => Category::find()->addOrderBy('root, lft'),
		'headingOptions'    => ['label' => 'Categories'],
		'isAdmin'           => true,                       // optional (toggle to enable admin mode)
		'displayValue'      => 1,                           // initial display value
		//'softDelete'      => true,                        // normally not needed to change
		//'cacheSettings'   => ['enableCache' => true]      // normally not needed to change

]);

