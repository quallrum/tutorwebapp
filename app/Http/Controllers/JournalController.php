<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalColumn;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class JournalController extends Controller{
	
	public function group(){
		$this->authorize('journal.changeGroup');
		$user = Auth::user();

		if($user->role->name == 'admin') 		$groups = Group::all();
		else if($user->role->name == 'tutor') 	$groups = Group::ofTutor($user);
		else									$groups = [];

		return view('journal.select-group', [
			'groups' => $groups,
		]);
	}

	public function subject(Group $group){
		$this->authorize('journal.changeSubject');
		$user = Auth::user();

		if($user->role->name == 'admin' 
			or $user->role->name == 'monitor'
			or $user->role->name == 'group')	$subjects = Subject::ofGroup($group)->get();
		else if($user->role->name == 'tutor')	$subjects = Subject::ofGroup($group)->ofTutor($user)->get();
		else									$subjects = [];
		
		return view('journal.select-subject', [
			'group'		=> $group,
			'subjects'	=> $subjects,
		]);
	}

	public function show(Group $group, Subject $subject){
		$this->authorize('journal.view');
		
		$header = JournalColumn::where('group_id', $group->id)
			->where('subject_id', $subject->id)
			->orderBy('created_at')
			->get();

		$table = [];
		$columns = $header->pluck('id');
		foreach ($group->students as $student) {
			$table[$student->id] = Journal::where('student_id', $student->id)
				->whereIn('column_id', $columns)
				->orderBy('column_id')
				->get();
		}

		return view('journal.show', [
			'user'		=> Auth::user(),
			'group' 	=> $group,
			'subject'	=> $subject,
			'header'	=> $header,
			'table'		=> $table,
		]);
	}

	public function file(Group $group, Subject $subject){

		$header = JournalColumn::where('group_id', $group->id)
			->where('subject_id', $subject->id)
			->orderBy('created_at')
			->get();

		$table = [];
		$columns = $header->pluck('id');
		$header = $header->all();
		foreach ($group->students as $student) {
			$table[$student->id] = Journal::where('student_id', $student->id)
				->whereIn('column_id', $columns)
				->orderBy('column_id')
				->get();
		}

		$N = count($header);
		if ($N < 27) {
			$n = 27 - $N;
			array_push($header, ...array_fill(0, $n, new JournalColumn));
			foreach ($group->students as $student) {
				$table[$student->id] = $table[$student->id]->all();
				array_push($table[$student->id], ...array_fill(0, $n, new Journal));
			}
		}

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Журнал '.$group->title);
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
				->setValue($header[$i]->date)
				->setDataType(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->getStyle()->applyFromArray($style_common)
				->getAlignment()->setTextRotation(90);

			$column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 3);
			$sheet->getColumnDimension($column)->setWidth(4);
		}

		$i = 2;
		foreach ($group->students as $student) {
			$sheet->getRowDimension($i)->setRowHeight(16);
			
			$sheet->getCell('A'.$i)->setValue($i - 1)
				->getStyle()->applyFromArray($style_number);

			$sheet->getCell('B'.$i)->setValue($student->lastname.' '.$student->firstname)
				->getStyle()->applyFromArray($style_fullname);

			$j = 3;
			foreach ($table[$student->id] as $record) {
				$sheet->getCellByColumnAndRow($j, $i)->setValue($record->value)
					->getStyle()->applyFromArray($style_common);
				$j++;
			}
			$i++;
		}
		
		$writer = new Xlsx($spreadsheet);

		return response()->streamDownload(function() use ($writer){
			$writer->save('php://output');
		}, $group->title.'.xlsx');
	}

	public function update(Group $group, Subject $subject, Request $request){
		$this->authorize('journal.edit');

		$request->validate([
			'journal'		=> ['nullable', 'array'],
			'new_journal'	=> ['nullable', 'array'],
			'delete'		=> ['nullable', 'array'],
		]);

		$failed = [];
		$failed_new = [];
		$failed_delete = [];
		$errors = [];

		if($request->input('journal')){
			foreach ($request->input('journal') as $id => $value) {
				$record = Journal::find($id);

				if($record and ($record->editable() or Auth::user()->role->name == 'admin') and $record->column->subject_id == $subject->id){
					$record->value = $value;

					if(!$record->save()){
						$failed[] = $id;
						Log::error('User '.Auth::user()->id.' failed to update journal record '.$id.' due to unexpected error');
					}
				}
				else{
					$failed[] = $id;
					Log::warning('User '.Auth::user()->id.' failed to update journal record '.$id.': record not editable, record doesn\'t belong to subject '.$subject->id.' or record not found');
				}
			}
		}
		
		if($request->input('new_journal')){
			if(JournalColumn::canAdd($group, $subject) or Auth::user()->role->name == 'admin'){
				foreach ($request->input('new_journal') as $new_column) {
					$column = new JournalColumn;
					$column->subject_id = $subject->id;
					$column->group_id = $group->id;

					if(!$column->save() and !$column->save()){
						Log::warning('User '.Auth::user()->id.' failed to create new journal column due to unexpected error');
						$failed_new[] = 'column';
						continue;
					}

					foreach ($new_column as $id => $value) {
						if($group->hasStudent($id)){
							$record = new Journal;
							$record->column_id = $column->id;
							$record->student_id = $id;
							$record->value = $value;

							if(!$record->save()){
								$failed_new[] = $id;
								Log::error('User '.Auth::user()->id.' failed to create journal record for student '.$id.' due to unexpected error');
							}
						}
						else{
							Log::warning('User '.Auth::user()->id.' failed to create journal record for student '.$id.': student doesn\'t belong to group '.$group->id);
						}
					}	
				}
			}
			else{
				$errors[] = 'You can add only one column per day';
				Log::warning('User '.Auth::user()->id.' tried to add more than one journal column today for group '.$group->id.' and subject '.$subject->id);
			}
		}

		if($request->has('delete')){
			foreach ($request->input('delete') as $id) {
				$column = JournalColumn::find($id);

				if($column and ($column->editable() or Auth::user()->role->name == 'admin') and $column->subject_id == $subject->id){
					if($column->delete() and $column->records()->delete()) Log::notice('User'.Auth::user()->id.' deleted column '.$id.'. Soft delete was used');
					else Log::error('User '.Auth::user()->id.' failed to delete journal column '.$id.' due to unexpected error');
				}
				else{
					$failed_delete[] = $id;
					Log::warning('User '.Auth::user()->id.' failed to delete journal column '.$id.': column not editable, column doesn\'t belong to subject '.$subject->id.', column not found or deleting failed.');
				}
			}
		}
		
		if($failed or $failed_new or $failed_delete) $errors[] = 'Not all records was updated, contact admin';

		if($request->wantsJson()){
			if($errors) return response()->json([
				'message'	=> $errors,
			], 500);

			return response()->json([
				'message'	=> 'Journal updated!'
			]);
		}

		if($errors) return back()->withErrors($errors);
		return back()->with('success', 'Journal updated');
	}

}
