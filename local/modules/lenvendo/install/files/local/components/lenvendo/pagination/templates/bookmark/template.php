<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<? \Bitrix\Main\UI\Extension::load('ui.bootstrap4'); ?>

<div class="bookmark-pagination">
	<nav>
	  	<ul class="pagination">
			<? if($arResult['SHOW_END']): ?>
		    <li class="page-item<?=!$arResult['FIRST_PAGE'] ? ' disabled' : ''?>">
		    	<a class="page-link" href="?<?=$arResult['PAGE_KEY']?>=<?=($p = $arResult['FIRST_PAGE']) ? $p : '#'?>"><<</a>
		    </li>
			<? endif; ?>

			<? if($arResult['SHOW_NEXT']): ?>
		    <li class="page-item<?=!$arResult['PREV_PAGE'] ? ' disabled' : ''?>">
		    	<a class="page-link" href="?<?=$arResult['PAGE_KEY']?>=<?=($p = $arResult['PREV_PAGE']) ? $p : '#'?>"><</a>
		    </li>
			<? endif; ?>


			<? foreach($arResult['PAGES_LIST'] as $page): ?>
			<li class="page-item<?=$page == $arResult['PAGE'] ? ' active' : ''?>">
				<a class="page-link" href="<?=$page == $arResult['PAGE'] ? '#' : '?'.$arResult['PAGE_KEY'].'='.$page?>"><?=$page?></a>
			</li>	
			<? endforeach; ?>

			
			<? if($arResult['SHOW_NEXT']): ?>
		    <li class="page-item<?=!$arResult['NEXT_PAGE'] ? ' disabled' : ''?>">
		    	<a class="page-link" href="?<?=$arResult['PAGE_KEY']?>=<?=($p = $arResult['NEXT_PAGE']) ? $p : '#'?>">></a>
		    </li>
		    <? endif; ?>

		    <? if($arResult['SHOW_END']): ?>
		    <li class="page-item<?=!$arResult['LAST_PAGE'] ? ' disabled' : ''?>">
		    	<a class="page-link" href="?<?=$arResult['PAGE_KEY']?>=<?=($p = $arResult['LAST_PAGE']) ? $p : '#'?>">>></a>
		    </li>
		    <? endif; ?>
	  	</ul>
	</nav>
</div>