<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<h1>List</h1>

<div>
	<div>
		<button class="btn btn-info" onclick="location.href='<?=$arResult['ADD_PAGE_URL']?>'">Добавить</button>
	</div>
	<div>
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
							<td><?=$item['FAVICON']?></td>
							<td><a href="<?=$item['URL']?>"><?=$item['URL']?></a></td>
							<td><a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a></td>	
						</tr>
					<? endforeach; ?>			
				</tbody>
			</table>
		<? endif; ?>
	</div>
</div>