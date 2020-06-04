<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<h1>List</h1>

<div>
	<? foreach($arResult['ITEMS'] as $item): ?>
		<div>
			<a href="<?=$item['URL']?>"><?=$item['NAME']?></a>
		</div>
	<? endforeach; ?>
</div>