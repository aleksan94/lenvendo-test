<?
namespace Lenvendo\Office;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\{Font, Border, Alignment, Fill};

/**
 * 
 */
class Excel
{
	// первый символ столбца (A) для итерации таблицы
	const FIRST_COL_HEX = 41;
	const TMP_PATH = "/upload/excel.xlsx"; 

	private $spreadsheet;

	public function generateTable($arRows)
	{
		/* Init */
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		/* ---- */

		$rowNum = 1;
		foreach($arRows as $row) {
			$rowStyle = ($s = $row['style']) ? $s : [];
			$items = $row['items'];

			$colNum = self::FIRST_COL_HEX;
			foreach($items as $cell) {
				// переводим порядковый номер столбца в символьный код (41 => A, 42 => B)
				$colNumBin = hex2bin($colNum);

				// формируем номер ячейки таблицы (например, A1)
				$cellNum = $colNumBin.$rowNum;

				$cellStyle = ($s = $cell['style']) ? $s : [];
				$cellValue = $cell['value'];

				// результирующий стиль row + cell
				$style = array_merge($rowStyle, $cellStyle);
				// проставляем стиль
				$sheet->getStyle($cellNum)->applyFromArray($style);
				// указываем значение ячейки
				$sheet->setCellValue($cellNum, $cellValue);

				$colNum++;
			}

			$rowNum++;
		}

		$this->spreadsheet = $spreadsheet;

		return $this;
	}

	public function download($fileName = 'Excel.xlsx')
	{
		global $APPLICATION;
		$APPLICATION->RestartBuffer();
		header('Content-Type: application/octet-stream');
    	header('Content-Disposition: attachment; filename=' . basename($fileName));

    	echo self::getContent();
    	die();
		/* ------- */
	}

	public function save($filePath)
	{
		$writer = new Xlsx($this->spreadsheet);
		return $writer->save($filePath);
	}

	public function getContent()
	{
		$writer = new Xlsx($this->spreadsheet);

		$path = $_SERVER['DOCUMENT_ROOT'].self::TMP_PATH;

		$dir = dirname($path);
		if(!file_exists($dir)) mkdir($dir, 0777, true);

		$writer->save($path);
		$content = file_get_contents($path);
		unlink($path);
		return $content;
	}
	
}