<?php


class Cli
{
	private $flags = array(
		'v' => array(
			'name' => 'verbose',
			'details' => "Shows files and directories downloaded and created.",
			'set' => true
		)
	);
	private $commit_hash;
	private $template_files = array(
		'default.html',
		'js/common.js',
		'css/reset.css',
		'css/screen.css',
		'css/ie6.css',
		'css/ie7.css'
	);
	private $out = '';
	private $argv = array();

	public function __construct($argv)
	{
		$this->argv = $argv;

		// Removing self (filename) from argv
		array_shift($this->argv);
		
		// Check if any flags are set and if so we'll remove it from argv
		$this->set_flags();

		if (count($this->argv))
		{
			$dir = rtrim(array_shift($this->argv), '/') . '/';
			if (!is_dir($dir) && !@mkdir($dir))
				$this->error("Could not create directory \"$dir\"");

			if (!is_writable($dir))
				$this->error("Directory is not writable \"$dir\"");

			if (!$this->dir_empty($dir))
				$this->error("Directory is not empty \"$dir\"");
			
			$this->create_dirs($dir);

			$repo = json_decode(file_get_contents('http://github.com/api/v2/json/repos/show/jamestkirk/devbox/branches'));
			if (isset($repo->branches->master))
				$this->commit_hash = $repo->branches->master;
			else
				$this->error("Could not fetch latest commit hash.");

			
			foreach ($this->template_files as $file)
			{
				if (!is_dir($dir) && !@mkdir($dir))
					$this->error("Could not create directory \"$dir\"");

				$this->flush("Copying http://github.com/jamestkirk/devbox/raw/{$this->commit_hash}/html_css_js/$file");
				$success = @copy("http://github.com/jamestkirk/devbox/raw/{$this->commit_hash}/html_css_js/$file", $dir . $file);
				if (!$success)
				{
					$this->flush("Failed Copying http://github.com/jamestkirk/devbox/raw/{$this->commit_hash}/html_css_js/$file");
					break;
				}
			}
			$this->out .= $success ? 'Transfer Complete.' : 'Error: Transfer Failed.';
		}
		else
			$this->show_help();
	}
	
	private function set_flags()
	{
		if (strpos($this->argv[0], '-') === 0)
		{
			$flags = str_split(@substr($this->argv[0], 1));
			foreach ($flags as $flag)
			{
				if (isset($this->flags[$flag]))
					$this->flags[$flag]['set'] = true;
			}

			// Remove flags because we don't need them anymore
			array_shift($this->argv);
		}
	}
	
	private function create_dirs($cwd)
	{
		$create_dirs = array();
		foreach ($this->template_files as $file)
		{
			$path = explode('/', $file);

			// Remove file
			@array_pop($path);

			// Get depth of file
			$depth = @count($path);
			
			$path = @implode('', $path);
			if ($path && !in_array($dir = array($depth, $path), $create_dirs))
				$create_dirs[] = $dir;
		}
		
		// Sort by depth
		sort($create_dirs);
		
		// Check if directory exists and is writable
		foreach ($create_dirs as $dir)
		{
			$dir = array_pop($dir);
			if (!@mkdir($cwd . $dir))
				$this->error("Could not create directory \"$cwd . $dir\"");
		}
	}
	private function flush($string)
	{
		if ($this->flags['v']['set'])
			echo $string . "\n";
	}
	
	private function show_help()
	{
		$this->out = "usage: php " . basename(__FILE__) . " [options] [directory_name]\n";
		$this->out .= $this->get_options();
		$this->out .= "requirements:
- PHP binary must be installed with curl extension
- allow_url_fopen must be set to true on your php.ini
";
	}
 	private function get_options()
	{
		$header = array(
			'Flag' => array('name' => 'Name', 'details' => 'Details')
		);
		$out = '';
		foreach (array_merge($header, $this->flags) as $flag => $v)
		{
			$v['details'] = explode("\n", $v['details']);
			$out .= str_pad((strlen($flag) == 1 ? '-' : '') . $flag, 10, ' ') . str_pad($v['name'], 20, ' ');
			for ($i = 0, $length = count($v['details']); $i < $length; $i++)
			{
				if ($i == 0)
					$out .= $v['details'][$i] . "\n";
				else
					$out .= str_repeat(' ', 30) . $v['details'][$i];
			}
		}
		
		return $out . "\n";
	}
	
	private function dir_empty($dir)
	{
		return (($files = @scandir($dir)) && count($files) <= 2); 
	}

	private function error($error)
	{
		die("Error: $error\n");
	}

	public function __toString()
	{
		return $this->out . "\n";
	}

}

echo new Cli($argv);

?>