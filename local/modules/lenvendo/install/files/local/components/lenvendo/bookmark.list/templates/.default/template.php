<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<? $APPLICATION->SetTitle('Список закладок'); ?>

<div class="bookmark-list">
	<div class="d-flex justify-content-between align-items-center">
		<div>
			<? if($arParams['SHOW_EXPORT_TO_EXCEL'] === 'Y'): ?>
				<button class="btn btn-success btn-sm" onclick="location.href='?AJAX_ACTION=exportToExcel'">Выгрузить в Excel</button>
			<? endif; ?>
		</div>
		<div>
			<button class="btn btn-info" onclick="location.href='<?=$arResult['ADD_PAGE_URL']?>'">Добавить</button>
		</div>
	</div>

	<? if($arResult['ITEMS']): ?>
		<div class="mt-3">
			<table class="table table-hover">
				<thead>
					<th>
						<? $this->__component->showHeadSpan('DATE_CREATE', 'Дата добавления'); ?>
					</th>
					<th>
						<span>Favicon</span>
					</th>
					<th>
						<? $this->__component->showHeadSpan('PROPERTY_URL', 'URL страницы'); ?>
					</th>
					<th>
						<? $this->__component->showHeadSpan('NAME', 'Заголовок страницы'); ?>
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