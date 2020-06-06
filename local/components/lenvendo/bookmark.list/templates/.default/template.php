<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<? $APPLICATION->SetTitle('Список закладок'); ?>

<div class="bookmark-list">
	<div class="d-flex justify-content-end">
		<button class="btn btn-info" onclick="location.href='<?=$arResult['ADD_PAGE_URL']?>'">Добавить</button>
	</div>

	<? if($arResult['ITEMS']): ?>
		<div class="mt-3">
			<table class="table table-hover">
				<thead>
					<th>
						<span class="bookmark-list__sort<?=$arResult['SORT_KEY'] === 'DATE_CREATE' ? ' '.strtolower($arResult['SORT']) : ''?>" data-key="DATE_CREATE">Дата добавления</span>
					</th>
					<th>Favicon</th>
					<th>
						<span class="bookmark-list__sort<?=$arResult['SORT_KEY'] === 'PROPERTY_URL' ? ' '.strtolower($arResult['SORT']) : ''?>" data-key="PROPERTY_URL">URL страницы</span>
					</th>
					<th>
						<span class="bookmark-list__sort<?=$arResult['SORT_KEY'] === 'NAME' ? ' '.strtolower($arResult['SORT']) : ''?>" data-key="NAME">Заголовок страницы</span>
					</th>
				</thead>
				<tbody>
					<? foreach($arResult['ITEMS'] as $item): ?>
						<tr>
							<td><?=$item['DATE_CREATE']?></td>
							<td class="text-center">
								<? if($item['FAVICON']): ?>
								<a href="<?=$item['FAVICON']?>" target="_blank"><img src="<?=$item['FAVICON']?>" width="16" height="16"></a>
								<? endif; ?>
							</td>
							<td><a href="<?=$item['URL']?>"><?=$item['URL']?></a></td>
							<td><a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a></td>	
						</tr>
					<? endforeach; ?>			
				</tbody>
			</table>
		</div>

		<? if($arParams['SHOW_PAGINATION'] === 'Y'): ?>
		<div>
			<? $APPLICATION->IncludeComponent(
				'lenvendo:pagination', 
				'bookmark', 
				[
					'PAGE_SIZE' => $arResult['PAGE_SIZE'],
					//'MAX_PAGES' => 5, 
					'PAGE_KEY' => 'page', 
					'COUNT_ITEMS' => $arResult['COUNT_ITEMS'],
				]); 
			?>
		</div>
		<? endif; ?>

	<? else: ?>
		<div class="text-center p-2">
			<h3 class="text-info">Список закладок пока пуст</h3>
		</div>
	<? endif; ?>
</div>