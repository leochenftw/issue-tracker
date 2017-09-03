<?php
/*
 * @file ImportClients.php
 *
 * make pages  rebuild their own fulltext
 */

class ImportClients extends BuildTask {
	protected $title = 'Import clients';
	protected $description = 'From the old site';

	protected $enabled = true;

	public function run($request) {
        if ($request->getHeader('User-Agent') != 'CLI') {
            print '<span style="color: red; font-weight: bold; font-size: 24px;">This task is for CLI use only</span><br />';
            print '<em style="font-size: 14px;"><strong>Usage</strong>: sake dev/tasks/ImportClients \'file=&lt;absolute_path_to_file&gt;\'</em>';
            return;
        }

        if ($file = $request->getVar('file')) {
            if (file_exists($file)) {
                $lines = array_map('str_getcsv', file($file));
    			if (!is_array($lines[0])) {
    				print 'Wrong CSV format';
                    print PHP_EOL;
    				die;
    			}

    			$n = 0;

    			for ($i = 1; $i < count($lines); $i++) {

                    $role               =   new ClientPage();
                    $role->Title        =   $lines[$i][0];
                    $role->Code         =   $lines[$i][1];

                    $role->writeToStage('Stage');
                    $role->writeToStage('Live');

                    $n++;
                }

                print $n.' client(S) imported';
                print PHP_EOL;

            } else {
                print 'File doesn\'t exist!';
                print PHP_EOL;
                print PHP_EOL;
            }
        } else {
            print 'CSV file is not given!';
            print PHP_EOL;
            print 'Usage: sake dev/tasks/ImportClients \'file=<absolute_path_to_file>\'';
            print PHP_EOL;
            print PHP_EOL;
        }
	}


}
