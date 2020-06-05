<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<? $APPLICATION->SetTitle('Список закладок'); ?>

<div>
	<div class="d-flex justify-content-end">
		<button class="btn btn-info" onclick="location.href='<?=$arResult['ADD_PAGE_URL']?>'">Добавить</button>
	</div>

	<div class="mt-3">
		<? if($arResult['ITEMS']): ?>
			<table class="table table-hover">
				<thead>
					<th>Дата добавления</th>
					<th>Favicon</th>
					<th>URL страницы</th>
					<th>Заголовок страницы</th>
				</thead>
				<tbody>
					<? foreach($arResult['ITEMS'] as $item): ?>
						<tr>
							<td><?=$item['DATE_CREATE']?></td>
							<td class="text-center"><a href="<?=$item['FAVICON']?>" target="_blank"><img src="<?=$item['FAVICON']?>" width="16" height="16"></a></td>
							<td><a href="<?=$item['URL']?>"><?=$item['URL']?></a></td>
							<td><a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a></td>	
						</tr>
					<? endforeach; ?>			
				</tbody>
			</table>
		<? endif; ?>
	</div>

	<div>
		<? $APPLICATION->IncludeComponent(
			'lenvendo:pagination', 
			'bookmark', 
			[
				//'PAGE_SIZE' => 3,
				//'MAX_PAGES' => 5, 
				'PAGE_KEY' => 'p', 
				'COUNT_ITEMS' => $arResult['COUNT_ITEMS'],
			]); 
		?>
	</div>
</div>