<?php

namespace App\Traits;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Group;
use App\Models\Subject;

trait CreatesGroupSubjectExcel{

	protected $maxColumns = 27;
	
	public function createExcel(array $header, array $students, array $table, string $filename, string $sheetname){
		$n = count($header);
		if ($n < $this->maxColumns) array_push($header, ...array_fill(0, $this->maxColumns - $n, ''));

		if(count($students) != count($table)) throw new \Exception('Amount of students and table rows are not the same');
		
		for ($i = 0; $i < count($table); $i++) { 
			if(!isset($table[$i])) throw new \Exception('The table must be an ordered array with sequential integer keys');
			
			$n = count($table[$i]);
			if ($n < $this->maxColumns) array_push($table[$i], ...array_fill(0, $this->maxColumns - $n, ''));
		}

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle($sheetname);
		$sheet->getPageSetup()
			->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
			->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->getPageMargins()
			->setTop(0.4)->setBottom(0.4)
			->setLeft(0.4)->setRight(0.4);

		$borders = [
			'top'		=> [
				'borderStyle' 	=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color'			=> ['rgb' => '000000'],
			],
			'bottom'	=> [
				'borderStyle' 	=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color'			=> ['rgb' => '000000'],
			],
			'left'		=> [
				'borderStyle' 	=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color'			=> ['rgb' => '000000'],
			],
			'right'		=> [
				'borderStyle' 	=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color'			=> ['rgb' => '000000'],
			],
		];

		$style_common = [
			'alignment'	=> [
				'horizontal'	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical'		=> \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			],
			'borders'	=> $borders,
		];

		$style_fullname = [
			'alignment'	=> [
				'horizontal'	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				'vertical'		=> \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			],
			'borders'	=> $borders,
			'alignment'	=> ['wrapText' => true],
		];

		$style_number = [
			'alignment'	=> [
				'horizontal'	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'vertical'		=> \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			],
			'borders'	=> $borders,
		];

		$sheet->getColumnDimension('A')->setWidth(3);
		$sheet->getColumnDimension('B')->setWidth(27);
		$sheet->getRowDimension('1')->setRowHeight(30);
		
		$sheet->getCell('A1')->getStyle()->applyFromArray($style_common);
		$sheet->getCell('B1')->getStyle()->applyFromArray($style_common);

		$sheet->fromArray(['№', 'ПІБ']);
		for($i = 0; $i < count($header); $i++){
			$sheet->getCellByColumnAndRow($i + 3, 1)
				->setValue($header[$i])
				->setDataType(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->getStyle()->applyFromArray($style_common)
				->getAlignment()->setTextRotation(90);

			$column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 3);
			$sheet->getColumnDimension($column)->setWidth(4);
		}

		for ($i = 0; $i < count($table); $i++) {
			$sheet->getRowDimension($i + 2)->setRowHeight(16);
			
			$sheet->getCell('A'.($i + 2))->setValue($i + 1)
				->getStyle()->applyFromArray($style_number);

			$sheet->getCell('B'.($i + 2))->setValue($students[$i])
				->getStyle()->applyFromArray($style_fullname);

			for ($j = 0; $j < count($table[$i]); $j++) {
				$sheet->getCellByColumnAndRow($j + 3, $i + 2)->setValue($table[$i][$j])
					->getStyle()->applyFromArray($style_common);
				
			}
		}
		
		return new Xlsx($spreadsheet);
	}
}

?>