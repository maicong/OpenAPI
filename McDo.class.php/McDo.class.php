<?php
/**
 * McDo
 *
 * 后端处理统一类
 *
 * @package     McDo
 * @author      MaiCong
 * @copyright   Copyright (c) 2012 - 2014, McDo, Inc.
 * @license     http://maicong.me/license.html
 * @link        http://maicong.me
 * @date        2014-07-23 11:26:09
 * @since       Version 1.1
 */

class McDo {

	/*-----参数配置---------------------------------------*/

	protected $base_path = '';// 默认根目录

	protected $base_url = '';// 默认url

	protected $cache_path = '';// 缓存路径

	protected $charset = 'UTF-8';// 程序编码

	protected $time_reference = 'local';// 时间参考

	protected $cookie_secure = FALSE;// 只允许安全链接(HTTPS)设置cookie

	protected $cookie_prefix = '__mc_';// cookie前缀

	protected $cookie_path = '/';// cookie路径

	protected $cookie_domain = '';// cookie域名

	protected $csrf_cookie_name = 'csrf_cknm';// CSRF保护Cookie名称

	protected $csrf_token_name = 'csrf_tknm';// CSRF保护Cookie标记名称

	protected $csrf_expire = 7200;// CSRF保护Cookie有效期

	protected $db_enable = FALSE;

	protected $db_type = 'mysql';//数据库连接类型

	protected $db_server = 'localhost';// 数据库地址

	protected $db_user = 'root';// 数据库用户名

	protected $db_pwd = 'root';// 数据库密码

	protected $db_file = '';// SQLite数据库地址

	protected $db_port = 3306;// 数据库连接端口

	protected $db_charset = 'utf8';// 数据库字符集

	protected $db_name = '';// 数据库表名

	protected $db_option = array();// 其他设置

	protected $db_queryString;// 查询变量

	protected $log_threshold = 1;

	protected $log_path = '';

	protected $log_date_format = 'Y-m-d H:i:s';

	protected $log_enabled = TRUE;

	protected $log_levels = array('ERROR' => '1', 'DEBUG' => '2', 'INFO' => '3', 'ALL' => '4');

	protected $memcached;

	protected $memcached_enable = FALSE;

	protected $memcached_host = '127.0.0.1';

	protected $memcached_port = '11211';

	protected $memcached_persistent = TRUE;

	protected $memcached_weight = 1;

	protected $csrf_hash = '';// CSRF保护Cookie随机哈希值

	protected $xss_hash = '';// XSS保护随机哈希值

	// 绝不允许的字符串列表
	protected $_never_allowed_str = array(
		'document.cookie' => '[removed]',
		'document.write'  => '[removed]',
		'.parentNode'     => '[removed]',
		'.innerHTML'      => '[removed]',
		'window.location' => '[removed]',
		'-moz-binding'    => '[removed]',
		'<!--'            => '&lt;!--',
		'-->'             => '--&gt;',
		'<![CDATA['       => '&lt;![CDATA[',
		'<comment>'       => '&lt;comment&gt;'
	);

	// 绝不允许的字符串列表，正则表达式替换名单
	protected $_never_allowed_regex = array(
		'javascript\s*:',
		'expression\s*(\(|&\#40;)', // CSS and IE
		'vbscript\s*:', // IE, surprise!
		'Redirect\s+302',
		"([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
	);

	// MIME类型
	protected $mimes = array(
		'ai'    => 'application/postscript',
		'aif'   => 'audio/x-aiff',
		'aifc'  => 'audio/x-aiff',
		'aiff'  => 'audio/x-aiff',
		'art'   => 'image/x-jg',
		'asc'   => 'text/plain',
		'asf'   => 'video/x-ms-asf',
		'asx'   => 'video/x-ms-asf',
		'au'    => 'audio/basic',
		'avi'   => 'video/x-msvideo',
		'bak'   => 'application/x-trash',
		'bat'   => 'application/x-msdos-program',
		'bcpio' => 'application/x-bcpio',
		'bin'   => 'application/macbinary',
		'bmp'   => array('image/bmp', 'image/x-windows-bmp'),
		'book'  => 'application/x-maker',
		'c'     => 'text/x-csrc',
		'c++'   => 'text/x-c++src',
		'cab'   => 'application/vnd.ms-cab-compressed',
		'cat'   => 'application/vnd.ms-pki.seccat',
		'cc'    => 'text/x-c++src',
		'cdf'   => 'application/x-cdf',
		'cdr'   => 'image/x-coreldraw',
		'cdt'   => 'image/x-coreldrawtemplate',
		'cdy'   => 'application/vnd.cinderella',
		'chrt'  => 'application/x-kchart',
		'class' => 'application/octet-stream',
		'cls'   => 'text/x-tex',
		'com'   => 'application/x-msdos-program',
		'cpio'  => 'application/x-cpio',
		'cpp'   => 'text/x-c++src',
		'cpt'   => 'application/mac-compactpro',
		'crl'   => 'application/x-pkcs7-crl',
		'crt'   => 'application/x-x509-ca-cert',
		'csh'   => 'text/x-csh',
		'csm'   => 'application/cu-seeme',
		'css'   => 'text/css',
		'csv'   => array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
		'cu'   => 'application/cu-seeme',
		'cxx'  => 'text/x-c++src',
		'dcr'  => 'application/x-director',
		'deb'  => 'application/x-debian-package',
		'dif'  => 'video/x-dv',
		'diff' => 'text/plain',
		'dir'  => 'application/x-director',
		'djv'  => 'image/x-djvu',
		'djvu' => 'image/x-djvu',
		'dl'   => 'video/dl',
		'dll'  => 'application/octet-stream',
		'dmg'  => 'application/x-apple-diskimage',
		'dms'  => 'application/octet-stream',
		'doc'  => 'application/msword',
		'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'),
		'dot' => 'application/msword',
		'dv'  => 'video/x-dv',
		'dvi' => 'application/x-dvi',
		'dxr' => 'application/x-director',
		'eml' => 'message/rfc822',
		'eps' => 'application/postscript',
		'etx' => 'text/x-setext',
		'exe' => array('application/octet-stream', 'application/x-msdownload'),
		'ez'       => 'application/andrew-inset',
		'fb'       => 'application/x-maker',
		'fbdoc'    => 'application/x-maker',
		'fig'      => 'application/x-xfig',
		'fli'      => 'video/fli',
		'fm'       => 'application/x-maker',
		'frame'    => 'application/x-maker',
		'frm'      => 'application/x-maker',
		'gcf'      => 'application/x-graphing-calculator',
		'gf'       => 'application/x-tex-gf',
		'gif'      => 'image/gif',
		'gl'       => 'video/gl',
		'gnumeric' => 'application/x-gnumeric',
		'gsf'      => 'application/x-font',
		'gsm'      => 'audio/x-gsm',
		'gtar'     => 'application/x-gtar',
		'gz'       => 'application/x-gzip',
		'h'        => 'text/x-chdr',
		'h++'      => 'text/x-c++hdr',
		'hdf'      => 'application/x-hdf',
		'hh'       => 'text/x-c++hdr',
		'hpp'      => 'text/x-c++hdr',
		'hqx'      => 'application/mac-binhex40',
		'hta'      => 'application/hta',
		'htm'      => 'text/html',
		'html'     => 'text/html',
		'hxx'      => 'text/x-c++hdr',
		'ica'      => 'application/x-ica',
		'ice'      => 'x-conference/x-cooltalk',
		'ico'      => 'image/x-icon',
		'ief'      => 'image/ief',
		'iges'     => 'model/iges',
		'igs'      => 'model/iges',
		'iii'      => 'application/x-iphone',
		'ins'      => 'application/x-internet-signup',
		'isp'      => 'application/x-internet-signup',
		'jad'      => 'text/vnd.sun.j2me.app-descriptor',
		'jar'      => 'application/x-java-archive',
		'java'     => 'text/x-java',
		'jng'      => 'image/x-jng',
		'jnlp'     => 'application/x-java-jnlp-file',
		'jpe'      => array('image/jpeg', 'image/pjpeg'),
		'jpeg' => array('image/jpeg', 'image/pjpeg'),
		'jpg' => array('image/jpeg', 'image/pjpeg'),
		'js'   => 'application/x-javascript',
		'json' => array('application/json', 'text/json'),
		'kar'   => 'audio/midi',
		'key'   => 'application/pgp-keys',
		'kil'   => 'application/x-killustrator',
		'kpr'   => 'application/x-kpresenter',
		'kpt'   => 'application/x-kpresenter',
		'ksp'   => 'application/x-kspread',
		'kwd'   => 'application/x-kword',
		'kwt'   => 'application/x-kword',
		'latex' => 'application/x-latex',
		'lha'   => 'application/octet-stream',
		'log'   => array('text/plain', 'text/x-log'),
		'lsf'   => 'video/x-la-asf',
		'lsx'   => 'video/x-la-asf',
		'ltx'   => 'text/x-tex',
		'lzh'   => 'application/octet-stream',
		'lzx'   => 'application/x-lzx',
		'm3u'   => 'audio/x-mpegurl',
		'maker' => 'application/x-maker',
		'man'   => 'application/x-troff-man',
		'mdb'   => 'application/msaccess',
		'me'    => 'application/x-troff-me',
		'mesh'  => 'model/mesh',
		'mid'   => 'audio/midi',
		'midi'  => 'audio/midi',
		'mif'   => 'application/vnd.mif',
		'mml'   => 'text/mathml',
		'mng'   => 'video/x-mng',
		'moc'   => 'text/x-moc',
		'mov'   => 'video/quicktime',
		'movie' => 'video/x-sgi-movie',
		'mp2'   => 'audio/mpeg',
		'mp3'   => array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
		'mpe'   => 'video/mpeg',
		'mpeg'  => 'video/mpeg',
		'mpega' => 'audio/mpeg',
		'mpg'   => 'video/mpeg',
		'mpga'  => 'audio/mpeg',
		'ms'    => 'application/x-troff-ms',
		'msh'   => 'model/mesh',
		'msi'   => 'application/x-msi',
		'mxu'   => 'video/vnd.mpegurl',
		'nb'    => 'application/mathematica',
		'nc'    => 'application/x-netcdf',
		'nwc'   => 'application/x-nwc',
		'o'     => 'application/x-object',
		'oda'   => 'application/oda',
		'ogg'   => 'application/ogg',
		'old'   => 'application/x-trash',
		'oza'   => 'application/x-oz-application',
		'p'     => 'text/x-pascal',
		'p7r'   => 'application/x-pkcs7-certreqresp',
		'pac'   => 'application/x-ns-proxy-autoconfig',
		'pas'   => 'text/x-pascal',
		'pat'   => 'image/x-coreldrawpattern',
		'pbm'   => 'image/x-portable-bitmap',
		'pcf'   => 'application/x-font',
		'pcf.Z' => 'application/x-font',
		'pcx'   => 'image/pcx',
		'pdb'   => 'chemical/x-pdb',
		'pdf'   => array('application/pdf', 'application/x-download'),
		'pfa'   => 'application/x-font',
		'pfb'   => 'application/x-font',
		'pgm'   => 'image/x-portable-graymap',
		'pgn'   => 'application/x-chess-pgn',
		'pgp'   => 'application/pgp-signature',
		'php'   => 'application/x-httpd-php',
		'php3'  => 'application/x-httpd-php',
		'php3p' => 'application/x-httpd-php3-preprocessed',
		'php4'  => 'application/x-httpd-php',
		'phps'  => 'application/x-httpd-php-source',
		'pht'   => 'application/x-httpd-php',
		'phtml' => 'application/x-httpd-php',
		'pk'    => 'application/x-tex-pk',
		'pl'    => 'application/x-perl',
		'pls'   => 'audio/x-scpls',
		'pm'    => 'application/x-perl',
		'png'   => array('image/png', 'image/x-png'),
		'pnm' => 'image/x-portable-anymap',
		'pot' => 'application/vnd.ms-powerpoint',
		'ppm' => 'image/x-portable-pixmap',
		'pps' => 'application/vnd.ms-powerpoint',
		'ppt' => array('application/powerpoint', 'application/vnd.ms-powerpoint'),
		'prf'     => 'application/pics-rules',
		'ps'      => 'application/postscript',
		'psd'     => 'application/x-photoshop',
		'qt'      => 'video/quicktime',
		'qtl'     => 'application/x-quicktimeplayer',
		'ra'      => 'audio/x-realaudio',
		'ram'     => 'audio/x-pn-realaudio',
		'rar'     => 'application/x-rar-compressed',
		'ras'     => 'image/x-cmu-raster',
		'rgb'     => 'image/x-rgb',
		'rm'      => 'audio/x-pn-realaudio',
		'roff'    => 'application/x-troff',
		'rpm'     => 'audio/x-pn-realaudio-plugin',
		'rss'     => 'application/rss+xml',
		'rtf'     => 'text/rtf',
		'rtx'     => 'text/richtext',
		'rv'      => 'video/vnd.rn-realvideo',
		'sct'     => 'text/scriptlet',
		'sd2'     => 'audio/x-sd2',
		'sda'     => 'application/vnd.stardivision.draw',
		'sdc'     => 'application/vnd.stardivision.calc',
		'sdd'     => 'application/vnd.stardivision.impress',
		'sdp'     => 'application/vnd.stardivision.impress',
		'sdw'     => 'application/vnd.stardivision.writer',
		'sea'     => 'application/octet-stream',
		'ser'     => 'application/x-java-serialized-object',
		'sgf'     => 'application/x-go-sgf',
		'sgl'     => 'application/vnd.stardivision.writer-global',
		'sh'      => 'text/x-sh',
		'shar'    => 'application/x-shar',
		'shtml'   => 'text/html',
		'sid'     => 'audio/prs.sid',
		'sik'     => 'application/x-trash',
		'silo'    => 'model/mesh',
		'sis'     => 'application/vnd.symbian.install',
		'sit'     => 'application/x-stuffit',
		'skd'     => 'application/x-koan',
		'skm'     => 'application/x-koan',
		'skp'     => 'application/x-koan',
		'skt'     => 'application/x-koan',
		'smf'     => 'application/vnd.stardivision.math',
		'smi'     => 'application/smil',
		'smil'    => 'application/smil',
		'snd'     => 'audio/basic',
		'so'      => 'application/octet-stream',
		'spl'     => 'application/x-futuresplash',
		'src'     => 'application/x-wais-source',
		'stc'     => 'application/vnd.sun.xml.calc.template',
		'std'     => 'application/vnd.sun.xml.draw.template',
		'sti'     => 'application/vnd.sun.xml.impress.template',
		'stl'     => 'application/vnd.ms-pki.stl',
		'stw'     => 'application/vnd.sun.xml.writer.template',
		'sty'     => 'text/x-tex',
		'sv4cpio' => 'application/x-sv4cpio',
		'sv4crc'  => 'application/x-sv4crc',
		'svg'     => 'image/svg+xml',
		'svgz'    => 'image/svg+xml',
		'swf'     => 'application/x-shockwave-flash',
		'swfl'    => 'application/x-shockwave-flash',
		'sxc'     => 'application/vnd.sun.xml.calc',
		'sxd'     => 'application/vnd.sun.xml.draw',
		'sxg'     => 'application/vnd.sun.xml.writer.global',
		'sxi'     => 'application/vnd.sun.xml.impress',
		'sxm'     => 'application/vnd.sun.xml.math',
		'sxw'     => 'application/vnd.sun.xml.writer',
		't'       => 'application/x-troff',
		'tar'     => 'application/x-tar',
		'taz'     => 'application/x-gtar',
		'tcl'     => 'text/x-tcl',
		'tex'     => 'text/x-tex',
		'texi'    => 'application/x-texinfo',
		'texinfo' => 'application/x-texinfo',
		'text'    => 'text/plain',
		'tgz'     => array('application/x-tar', 'application/x-gzip-compressed'),
		'tif'     => 'image/tiff',
		'tiff'    => 'image/tiff',
		'tk'      => 'text/x-tcl',
		'tm'      => 'text/texmacs',
		'torrent' => 'application/x-bittorrent',
		'tr'      => 'application/x-troff',
		'ts'      => 'text/texmacs',
		'tsp'     => 'application/dsptype',
		'tsv'     => 'text/tab-separated-values',
		'txt'     => 'text/plain',
		'uls'     => 'text/iuls',
		'ustar'   => 'application/x-ustar',
		'vcd'     => 'application/x-cdlink',
		'vcf'     => 'text/x-vcard',
		'vcs'     => 'text/x-vcalendar',
		'vor'     => 'application/vnd.stardivision.writer',
		'vrm'     => 'x-world/x-vrml',
		'vrml'    => 'x-world/x-vrml',
		'wad'     => 'application/x-doom',
		'wav'     => array('audio/x-wav', 'audio/wave', 'audio/wav'),
		'wax'   => 'audio/x-ms-wax',
		'wbmp'  => 'image/vnd.wap.wbmp',
		'wbxml' => 'application/wbxml',
		'wk'    => 'application/x-123',
		'wm'    => 'video/x-ms-wm',
		'wma'   => 'audio/x-ms-wma',
		'wmd'   => 'application/x-ms-wmd',
		'wml'   => 'text/vnd.wap.wml',
		'wmlc'  => 'application/wmlc',
		'wmls'  => 'text/vnd.wap.wmlscript',
		'wmlsc' => 'application/vnd.wap.wmlscriptc',
		'wmv'   => 'video/x-ms-wmv',
		'wmx'   => 'video/x-ms-wmx',
		'wmz'   => 'application/x-ms-wmz',
		'word'  => array('application/msword', 'application/octet-stream'),
		'wp5'   => 'application/wordperfect5.1',
		'wrl'   => 'x-world/x-vrml',
		'wsc'   => 'text/scriptlet',
		'wvx'   => 'video/x-ms-wvx',
		'wz'    => 'application/x-wingz',
		'xbm'   => 'image/x-xbitmap',
		'xht'   => 'application/xhtml+xml',
		'xhtml' => 'application/xhtml+xml',
		'xl'    => 'application/excel',
		'xlb'   => 'application/vnd.ms-excel',
		'xls'   => array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
		'xlsx' => array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'),
		'xml' => 'text/xml',
		'xpm' => 'image/x-xpixmap',
		'xsl' => 'text/xml',
		'xwd' => 'image/x-xwindowdump',
		'xyz' => 'chemical/x-xyz',
		'zip' => array('application/x-zip', 'application/zip', 'application/x-zip-compressed')
	);

	/*-----公共函数---------------------------------------*/

	//-----构造

	public function __construct($options = NULL) {
		$this->write_log('debug', "Input Class Initialized");

		if (is_array($options) && !empty($options)) {
			foreach ($options as $option => $value) {
				$this->$option = $value;
			}
		}

		if ($this->base_path === '') {
			$base_path       = isset($_SERVER['DOCUMENT_ROOT'])?$this->server('DOCUMENT_ROOT'):dirname(__FILE__);
			$this->base_path = rtrim(str_replace("\\", "/", $base_path), '/');
		}

		if ($this->base_url === '') {
			$base_url       = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
			$this->base_url = rtrim(str_replace("\\", "/", $base_url), '/');
		}

		$this->log_path = !empty($this->log_path)?rtrim(str_replace("\\", "/", $this->log_path), '/'):$this->base_path.'/mcdo/logs';

		$this->cache_path = !empty($this->cache_path)?rtrim(str_replace("\\", "/", $this->cache_path), '/'):$this->base_path.'/mcdo/cache';

		if (!is_dir($this->log_path) OR !$this->is_really_writable($this->log_path)) {
			$this->log_enabled = FALSE;
			exit("<p>logs文件夹不存在或不可写: <strong>{$this->log_path}</strong></p>");
		}

		// Append application specific cookie prefix
		if ($this->cookie_prefix) {
			$this->csrf_cookie_name = $this->cookie_prefix.$this->csrf_cookie_name;
		}

		if ($this->db_enable !== FALSE) {
			try {
				$commands = array();

				if (
					isset($this->db_port) &&
					is_int($this->db_port*1)
				) {
					$port = $this->db_port;
				}

				$set_charset = "SET NAMES '".$this->db_charset."'";
				$type        = strtolower($this->db_type);
				$is_port     = isset($port);

				switch ($type) {
					case 'mariadb':
						$type = 'mysql';

					case 'mysql':
						// Make MySQL using standard quoted identifier
						$commands[] = 'SET SQL_MODE=ANSI_QUOTES';

					case 'pgsql':
						$dsn        = $type.':host='.$this->db_server.($is_port?';port='.$port:'').';dbname='.$this->db_name;
						$commands[] = $set_charset;
						break;

					case 'sybase':
						$dsn        = 'dblib:host='.$this->db_server.($is_port?':'.$port:'').';dbname='.$this->db_name;
						$commands[] = $set_charset;
						break;

					case 'oracle':
						$dsn = 'oci:host='.$this->db_server.($is_port?';port='.$port:'').';dbname='.$this->db_name.';charset='.$this->db_charset;
						break;

					case 'mssql':
						$dsn = strpos(PHP_OS, 'WIN') !== FALSE?
						'sqlsrv:server='.$this->db_server.($is_port?','.$port:'').';database='.$this->db_name:
						'dblib:host='.$this->db_server.($is_port?':'.$port:'').';dbname='.$this->db_name;

						// Keep MSSQL QUOTED_IDENTIFIER is ON for standard quoting
						$commands[] = 'SET QUOTED_IDENTIFIER ON';
						$commands[] = $set_charset;
						break;

					case 'sqlite':
						$dsn           = $type.':'.$this->db_file;
						$this->db_user = NULL;
						$this->db_pwd  = NULL;
						break;
				}

				$this->db_pdo = new PDO(
					$dsn,
					$this->db_user,
					$this->db_pwd,
					$this->db_option
				);

				foreach ($commands as $value) {
					$this->db_pdo->exec($value);
				}
			}
			 catch (PDOException $e) {
				throw new Exception($e->getMessage());
			}

			$this->write_log('debug', "Database Class Initialized");
		}
		if ($this->memcached_enable !== FALSE) {
			if (!extension_loaded('memcache') AND !extension_loaded('memcached')) {
				$this->memcached_enable = FALSE;
				$this->write_log('error', 'The Memcached Extension must be loaded to use Memcached Cache.');
			} else {
				$this->_setup_memcached();
				$this->write_log('debug', "Memcached Class Initialized");
			}
		}
		if (
			preg_match('/./u', 'é') === 1// PCRE must support UTF-8
			 AND function_exists('iconv')// iconv must be installed
			 AND ini_get('mbstring.func_overload') != 1// Multibyte string function overloading cannot be enabled
			 AND $this->charset == 'UTF-8'// Application charset must be UTF-8
		) {
			define('UTF8_ENABLED', TRUE);
		} else {
			define('UTF8_ENABLED', FALSE);
		}

		// Set the CSRF hash
		$this->_csrf_set_hash();

		$this->_sanitize_globals();

		$this->write_log('debug', "Security Class Initialized");
	}

	//-----数据库

	public function db_select($table, $join, $columns = NULL, $where = NULL) {
		$query = $this->db_query($this->db_select_context($table, $join, $columns, $where));

		return $query?$query->fetchAll(
			(is_string($columns) && $columns != '*')?PDO::FETCH_COLUMN:PDO::FETCH_ASSOC
		):FALSE;
	}

	public function db_insert($table, $datas) {
		$lastId = array();

		// Check indexed or associative array
		if (!isset($datas[0])) {
			$datas = array($datas);
		}

		foreach ($datas as $data) {
			$keys    = array_keys($data);
			$values  = array();
			$columns = array();

			foreach ($data as $key => $value) {
				array_push($columns, $this->db_column_quote($key));

				switch (gettype($value)) {
					case 'NULL':
						$values[] = 'NULL';
						break;

					case 'array':
						preg_match("/\(JSON\)\s*([\w]+)/i", $key, $column_match);

						if (isset($column_match[0])) {
							$values[] = $this->db_quote(json_encode($value));
						} else {
							$values[] = $this->db_quote(serialize($value));
						}
						break;

					case 'boolean':
						$values[] = ($value?'1':'0');
						break;

					case 'integer':
					case 'double':
					case 'string':
						$values[] = $this->db_fn_quote($key, $value);
						break;
				}
			}

			$this->db_exec('INSERT INTO "'.$table.'" ('.implode(', ', $columns).') VALUES ('.implode($values, ', ').')');

			$lastId[] = $this->db_pdo->lastInsertId();
		}

		return count($lastId) > 1?$lastId:$lastId[0];
	}

	public function db_update($table, $data, $where = NULL) {
		$fields = array();

		foreach ($data as $key => $value) {
			preg_match('/([\w]+)(\[(\+|\-|\*|\/)\])?/i', $key, $match);

			if (isset($match[3])) {
				if (is_numeric($value)) {
					$fields[] = $this->db_column_quote($match[1]).' = '.$this->db_column_quote($match[1]).' '.$match[3].' '.$value;
				}
			} else {
				$column = $this->db_column_quote($key);

				switch (gettype($value)) {
					case 'NULL':
						$fields[] = $column.' = NULL';
						break;

					case 'array':
						preg_match("/\(JSON\)\s*([\w]+)/i", $key, $column_match);

						if (isset($column_match[0])) {
							$fields[] = $this->db_column_quote($column_match[1]).' = '.$this->db_quote(json_encode($value));
						} else {
							$fields[] = $column.' = '.$this->db_quote(serialize($value));
						}
						break;

					case 'boolean':
						$fields[] = $column.' = '.($value?'1':'0');
						break;

					case 'integer':
					case 'double':
					case 'string':
						$fields[] = $column.' = '.$this->db_fn_quote($key, $value);
						break;
				}
			}
		}

		return $this->db_exec('UPDATE "'.$table.'" SET '.implode(', ', $fields).$this->db_where_clause($where));
	}

	public function db_delete($table, $where) {
		return $this->db_exec('DELETE FROM "'.$table.'"'.$this->db_where_clause($where));
	}

	public function db_replace($table, $columns, $search = NULL, $replace = NULL, $where = NULL) {
		if (is_array($columns)) {
			$replace_query = array();

			foreach ($columns as $column => $replacements) {
				foreach ($replacements as $replace_search => $replace_replacement) {
					$replace_query[] = $column.' = REPLACE('.$this->db_column_quote($column).', '.$this->db_quote($replace_search).', '.$this->db_quote($replace_replacement).')';
				}
			}

			$replace_query = implode(', ', $replace_query);
			$where         = $search;
		} else {
			if (is_array($search)) {
				$replace_query = array();

				foreach ($search as $replace_search => $replace_replacement) {
					$replace_query[] = $columns.' = REPLACE('.$this->db_column_quote($columns).', '.$this->db_quote($replace_search).', '.$this->db_quote($replace_replacement).')';
				}

				$replace_query = implode(', ', $replace_query);
				$where         = $replace;
			} else {
				$replace_query = $columns.' = REPLACE('.$this->db_column_quote($columns).', '.$this->db_quote($search).', '.$this->db_quote($replace).')';
			}
		}

		return $this->db_exec('UPDATE "'.$table.'" SET '.$replace_query.$this->db_where_clause($where));
	}

	public function db_get($table, $columns, $where = NULL) {
		if (!isset($where)) {
			$where = array();
		}

		$where['LIMIT'] = 1;

		$data = $this->db_select($table, $columns, $where);

		return isset($data[0])?$data[0]:FALSE;
	}

	public function db_has($table, $join, $where = NULL) {
		$column = NULL;

		return $this->db_query('SELECT EXISTS('.$this->db_select_context($table, $join, $column, $where, 1).')')->fetchColumn() === '1';
	}

	public function db_count($table, $join = NULL, $column = NULL, $where = NULL) {
		return 0+($this->db_query($this->db_select_context($table, $join, $column, $where, 'COUNT'))->fetchColumn());
	}

	public function db_max($table, $join, $column = NULL, $where = NULL) {
		$max = $this->db_query($this->db_select_context($table, $join, $column, $where, 'MAX'))->fetchColumn();

		return is_numeric($max)?$max+0:$max;
	}

	public function db_min($table, $join, $column = NULL, $where = NULL) {
		$min = $this->db_query($this->db_select_context($table, $join, $column, $where, 'MIN'))->fetchColumn();

		return is_numeric($min)?$min+0:$min;
	}

	public function db_avg($table, $join, $column = NULL, $where = NULL) {
		return 0+($this->db_query($this->db_select_context($table, $join, $column, $where, 'AVG'))->fetchColumn());
	}

	public function db_sum($table, $join, $column = NULL, $where = NULL) {
		return 0+($this->db_query($this->db_select_context($table, $join, $column, $where, 'SUM'))->fetchColumn());
	}

	public function db_error() {
		return $this->db_pdo->errorInfo();
	}

	public function db_last_query() {
		return $this->db_queryString;
	}

	public function db_info() {
		$output = array(
			'server'     => 'SERVER_INFO',
			'driver'     => 'DRIVER_NAME',
			'client'     => 'CLIENT_VERSION',
			'version'    => 'SERVER_VERSION',
			'connection' => 'CONNECTION_STATUS'
		);

		foreach ($output as $key => $value) {
			$output[$key] = $this->db_pdo->getAttribute(constant('PDO::ATTR_'.$value));
		}

		return $output;
	}

	public function db_query($query) {
		$this->db_queryString = $query;

		return $this->db_pdo->query($query);
	}

	public function db_exec($query) {
		$this->db_queryString = $query;

		return $this->db_pdo->exec($query);
	}

	public function db_quote($string) {
		return $this->db_pdo->quote($string);
	}

	//-----Cookie

	// 设置一个 Cookie 的值
	public function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE) {
		if (is_array($name)) {
			// always leave 'name' in last place, as the loop will break otherwise, due to $$item
			foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'name') as $item) {
				if (isset($name[$item])) {
					$$item = $name[$item];
				}
			}
		}

		if ($prefix == '' AND $this->cookie_prefix != '') {
			$prefix = $this->cookie_prefix;
		}
		if ($domain == '' AND $this->cookie_domain != '') {
			$domain = $this->cookie_domain;
		}
		if ($path == '/' AND $this->cookie_path != '/') {
			$path = $this->cookie_path;
		}
		if ($secure == FALSE AND $this->cookie_secure != FALSE) {
			$secure = $this->cookie_secure;
		}

		if (!is_numeric($expire)) {
			$expire = time()-86500;
		} else {
			$expire = ($expire > 0)?time()+$expire:0;
		}

		setcookie($prefix.$name, $value, $expire, $path, $domain, $secure);
	}

	// 获取 Cookie 的值
	public function get_cookie($index = '', $xss_clean = TRUE) {
		$prefix = '';

		if (!isset($_COOKIE[$index]) && $this->cookie_prefix != '') {
			$prefix = $this->cookie_prefix;
		}

		return $this->_fetch_from_array($_COOKIE, $prefix.$index, $xss_clean);
	}

	// 删除一个 Cookie
	public function delete_cookie($name = '', $domain = '', $path = '/', $prefix = '') {
		$this->set_cookie($name, '', '', $domain, $path, $prefix);
	}

	//-----验证

	// 验证正在使用的PHP的版本号是否高于你所提供的版本号
	public function valid_php($version = '5.0.0') {
		static $_is_php;
		$version = (string) $version;

		if (!isset($_is_php[$version])) {
			$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0)?FALSE:TRUE;
		}

		return $_is_php[$version];
	}

	// 验证邮箱是否有效
	public function valid_email($email) {
		return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email))?FALSE:TRUE;
	}

	// 验证IP地址是否有效
	public function valid_ip($ip, $which = '') {
		$which = strtolower($which);

		// First check if filter_var is available
		if (is_callable('filter_var')) {
			switch ($which) {
				case 'ipv4':
					$flag = FILTER_FLAG_IPV4;
					break;
				case 'ipv6':
					$flag = FILTER_FLAG_IPV6;
					break;
				default:
					$flag = '';
					break;
			}

			return (bool) filter_var($ip, FILTER_VALIDATE_IP, $flag);
		}

		if ($which !== 'ipv6' && $which !== 'ipv4') {
			if (strpos($ip, ':') !== FALSE) {
				$which = 'ipv6';
			} elseif (strpos($ip, '.') !== FALSE) {
				$which = 'ipv4';
			} else {
				return FALSE;
			}
		}

		$func = '_valid_'.$which;
		return $this->$func($ip);
	}

	// 验证码函数
	public function create_captcha($data = '', $img_path = '', $img_url = '', $font_path = '') {
		$defaults = array('word' => '', 'img_path' => '', 'img_url' => '', 'img_width' => '150', 'img_height' => '30', 'font_path' => '', 'expiration' => 7200);

		foreach ($defaults as $key => $val) {
			if (!is_array($data)) {
				if (!isset($$key) OR $$key == '') {
					$$key = $val;
				}
			} else {
				$$key = (!isset($data[$key]))?$val:$data[$key];
			}
		}

		if ($img_path == '' OR $img_url == '') {
			return FALSE;
		}

		if (!@is_dir($img_path)) {
			return FALSE;
		}

		if (!is_writable($img_path)) {
			return FALSE;
		}

		if (!extension_loaded('gd')) {
			return FALSE;
		}

		// -----------------------------------
		// Remove old images
		// -----------------------------------

		list($usec, $sec) = explode(" ", microtime());
		$now              = ((float) $usec+(float) $sec);

		$current_dir = @opendir($img_path);

		while ($filename = @readdir($current_dir)) {
			if ($filename != "." and $filename != ".." and $filename != "index.html") {
				$name = str_replace(".jpg", "", $filename);

				if (($name+$expiration) < $now) {
					@unlink($img_path.$filename);
				}
			}
		}

		@closedir($current_dir);

		// -----------------------------------
		// Do we have a "word" yet?
		// -----------------------------------

		if ($word == '') {
			$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

			$str = '';
			for ($i = 0; $i < 8; $i++) {
				$str .= substr($pool, mt_rand(0, strlen($pool)-1), 1);
			}

			$word = $str;
		}

		// -----------------------------------
		// Determine angle and position
		// -----------------------------------

		$length = strlen($word);
		$angle  = ($length >= 6)?rand(-($length-6), ($length-6)):0;
		$x_axis = rand(6, (360/$length)-16);
		$y_axis = ($angle >= 0)?rand($img_height, $img_width):rand(6, $img_height);

		// -----------------------------------
		// Create image
		// -----------------------------------

		// PHP.net recommends imagecreatetruecolor(), but it isn't always available
		if (function_exists('imagecreatetruecolor')) {
			$im = imagecreatetruecolor($img_width, $img_height);
		} else {
			$im = imagecreate($img_width, $img_height);
		}

		// -----------------------------------
		//  Assign colors
		// -----------------------------------

		$bg_color     = imagecolorallocate($im, 255, 255, 255);
		$border_color = imagecolorallocate($im, 153, 102, 102);
		$text_color   = imagecolorallocate($im, 204, 153, 153);
		$grid_color   = imagecolorallocate($im, 255, 182, 182);
		$shadow_color = imagecolorallocate($im, 255, 240, 240);

		// -----------------------------------
		//  Create the rectangle
		// -----------------------------------

		ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $bg_color);

		// -----------------------------------
		//  Create the spiral pattern
		// -----------------------------------

		$theta   = 1;
		$thetac  = 7;
		$radius  = 16;
		$circles = 20;
		$points  = 32;

		for ($i = 0; $i < ($circles*$points)-1; $i++) {
			$theta = $theta+$thetac;
			$rad   = $radius*($i/$points);
			$x     = ($rad*cos($theta))+$x_axis;
			$y     = ($rad*sin($theta))+$y_axis;
			$theta = $theta+$thetac;
			$rad1  = $radius*(($i+1)/$points);
			$x1    = ($rad1*cos($theta))+$x_axis;
			$y1    = ($rad1*sin($theta))+$y_axis;
			imageline($im, $x, $y, $x1, $y1, $grid_color);
			$theta = $theta-$thetac;
		}

		// -----------------------------------
		//  Write the text
		// -----------------------------------

		$use_font = ($font_path != '' AND file_exists($font_path) AND function_exists('imagettftext'))?TRUE:FALSE;

		if ($use_font == FALSE) {
			$font_size = 5;
			$x         = rand(0, $img_width/($length/3));
			$y         = 0;
		} else {
			$font_size = 16;
			$x         = rand(0, $img_width/($length/1.5));
			$y         = $font_size+2;
		}

		for ($i = 0; $i < strlen($word); $i++) {
			if ($use_font == FALSE) {
				$y = rand(0, $img_height/2);
				imagestring($im, $font_size, $x, $y, substr($word, $i, 1), $text_color);
				$x += ($font_size*2);
			} else {
				$y = rand($img_height/2, $img_height-3);
				imagettftext($im, $font_size, $angle, $x, $y, $text_color, $font_path, substr($word, $i, 1));
				$x += $font_size;
			}
		}

		// -----------------------------------
		//  Create the border
		// -----------------------------------

		imagerectangle($im, 0, 0, $img_width-1, $img_height-1, $border_color);

		// -----------------------------------
		//  Generate the image
		// -----------------------------------

		$img_name = $now.'.jpg';

		ImageJPEG($im, $img_path.$img_name);

		$img = "<img src=\"$img_url$img_name\" width=\"$img_width\" height=\"$img_height\" style=\"border:0;
\" alt=\" \" />";

		ImageDestroy($im);

		return array('word' => $word, 'time' => $now, 'image' => $img);
	}

	//-----安全

	// 验证跨站点请求伪造保护
	public function csrf_verify() {
		// If it's not a POST request we will set the CSRF cookie
		if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
			return $this->csrf_set_cookie();
		}

		// Do the tokens exist in both the _POST and _COOKIE arrays?
		if (!isset($_POST[$this->csrf_token_name], $_COOKIE[$this->csrf_cookie_name])) {
			$this->csrf_show_error();
		}

		// Do the tokens match?
		if ($_POST[$this->csrf_token_name] != $_COOKIE[$this->csrf_cookie_name]) {
			$this->csrf_show_error();
		}

		// We kill this since we're done and we don't want to
		// polute the _POST array
		unset($_POST[$this->csrf_token_name]);

		// Nothing should last forever
		unset($_COOKIE[$this->csrf_cookie_name]);
		$this->_csrf_set_hash();
		$this->csrf_set_cookie();

		$this->write_log('debug', 'CSRF token verified');

		return $this;
	}

	// 设置跨站点请求伪造Cookie的保护
	public function csrf_set_cookie() {
		$expire        = time()+$this->csrf_expire;
		$secure_cookie = ($this->cookie_secure === TRUE)?1:0;

		if ($secure_cookie && (empty($_SERVER['HTTPS']) OR strtolower($_SERVER['HTTPS']) === 'off')) {
			return FALSE;
		}

		setcookie($this->csrf_cookie_name, $this->csrf_hash, $expire, $this->cookie_path, $this->cookie_domain, $secure_cookie);

		$this->write_log('debug', "CRSF cookie Set");

		return $this;
	}

	// 显示CSRF错误
	public function csrf_show_error() {
		exit('The action you have requested is not allowed.');
	}

	// 获取CSRF哈希值
	public function get_csrf_hash() {
		return $this->csrf_hash;
	}

	// GET安全过滤
	public function get($index = NULL, $xss_clean = TRUE) {
		// Check if a field has been provided
		if ($index === NULL AND !empty($_GET)) {
			$get = array();

			// loop through the full _GET array
			foreach (array_keys($_GET) as $key) {
				$get[$key] = $this->_fetch_from_array($_GET, $key, $xss_clean);
			}
			return $get;
		}

		return $this->_fetch_from_array($_GET, $index, $xss_clean);
	}

	// POST安全过滤
	public function post($index = NULL, $xss_clean = TRUE) {
		// Check if a field has been provided
		if ($index === NULL AND !empty($_POST)) {
			$post = array();

			// Loop through the full _POST array and return it
			foreach (array_keys($_POST) as $key) {
				$post[$key] = $this->_fetch_from_array($_POST, $key, $xss_clean);
			}
			return $post;
		}

		return $this->_fetch_from_array($_POST, $index, $xss_clean);
	}

	// 搜索POST和GET数据流
	public function get_post($index = '', $xss_clean = TRUE) {
		if (!isset($_POST[$index])) {
			return $this->get($index, $xss_clean);
		} else {
			return $this->post($index, $xss_clean);
		}
	}

	// SERVER安全过滤
	public function server($index = '', $xss_clean = TRUE) {
		return $this->_fetch_from_array($_SERVER, $index, $xss_clean);
	}

	// XSS过滤
	public function xss_clean($str, $is_image = FALSE) {
		/*
		 * Is the string an array?
		 *
		 */
		if (is_array($str)) {
			while (list($key) = each($str)) {
				$str[$key] = $this->xss_clean($str[$key]);
			}

			return $str;
		}

		/*
		 * Remove Invisible Characters
		 */
		$str = $this->remove_invisible_characters($str);

		// Validate Entities in URLs
		$str = $this->_validate_entities($str);

		/*
		 * URL Decode
		 *
		 * Just in case stuff like this is submitted:
		 *
		 * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
		 *
		 * Note: Use rawurldecode() so it does not remove plus signs
		 *
		 */
		$str = rawurldecode($str);

		/*
		 * Convert character entities to ASCII
		 *
		 * This permits our tests below to work reliably.
		 * We only convert entities that are within tags since
		 * these are the ones that will pose security problems.
		 *
		 */

		$str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);

		$str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", array($this, '_decode_entity'), $str);

		/*
		 * Remove Invisible Characters Again!
		 */
		$str = $this->remove_invisible_characters($str);

		/*
		 * Convert all tabs to spaces
		 *
		 * This prevents strings like this: ja  vascript
		 * NOTE: we deal with spaces between characters later.
		 * NOTE: preg_replace was found to be amazingly slow here on
		 * large blocks of data, so we use str_replace.
		 */

		if (strpos($str, "\t") !== FALSE) {
			$str = str_replace("\t", ' ', $str);
		}

		/*
		 * Capture converted string for later comparison
		 */
		$converted_string = $str;

		// Remove Strings that are never allowed
		$str = $this->_do_never_allowed($str);

		/*
		 * Makes PHP tags safe
		 *
		 * Note: XML tags are inadvertently replaced too:
		 *
		 * <?xml
		 *
		 * But it doesn't seem to pose a problem.
		 */
		if ($is_image === TRUE) {
			// Images have a tendency to have the PHP short opening and
			// closing tags every so often so we skip those and only
			// do the long opening tags.
			$str = preg_replace('/<\?(php)/i', "&lt;?\\1", $str);
		} else {
			$str = str_replace(array('<?', '?'.'>'), array('&lt;?', '?&gt;'), $str);
		}

		/*
		 * Compact any exploded words
		 *
		 * This corrects words like:  j a v a s c r i p t
		 * These words are compacted back to their correct state.
		 */
		$words = array(
			'javascript', 'expression', 'vbscript', 'script', 'base64',
			'applet', 'alert', 'document', 'write', 'cookie', 'window'
		);

		foreach ($words as $word) {
			$temp = '';

			for ($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++) {
				$temp .= substr($word, $i, 1)."\s*";
			}

			// We only want to do this when it is followed by a non-word character
			// That way valid stuff like "dealer to" does not become "dealerto"
			$str = preg_replace_callback('#('.substr($temp, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
		}

		/*
		 * Remove disallowed Javascript in links or img tags
		 * We used to do some version comparisons and use of stripos for PHP5,
		 * but it is dog slow compared to these simplified non-capturing
		 * preg_match(), especially if the pattern exists in the string
		 */
		do
		 {
			$original = $str;

			if (preg_match("/<a/i", $str)) {
				$str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", array($this, '_js_link_removal'), $str);
			}

			if (preg_match("/<img/i", $str)) {
				$str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", array($this, '_js_img_removal'), $str);
			}

			if (preg_match("/script/i", $str) OR preg_match("/xss/i", $str)) {
				$str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '[removed]', $str);
			}
		} while ($original != $str);

		unset($original);

		// Remove evil attributes such as style, onclick and xmlns
		$str = $this->_remove_evil_attributes($str, $is_image);

		/*
		 * Sanitize naughty HTML elements
		 *
		 * If a tag containing any of the words in the list
		 * below is found, the tag gets converted to entities.
		 *
		 * So this: <blink>
		 * Becomes: &lt;
		blink&gt;
		 */
		$naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
		$str     = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array($this, '_sanitize_naughty_html'), $str);

		/*
		 * Sanitize naughty scripting elements
		 *
		 * Similar to above, only instead of looking for
		 * tags it looks for PHP and JavaScript commands
		 * that are disallowed.  Rather than removing the
		 * code, it simply converts the parenthesis to entities
		 * rendering the code un-executable.
		 *
		 * For example: eval('some code')
		 * Becomes:     eval&#40;
		'some code'&#41;
		 */
		$str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);

		// Final clean up
		// This adds a bit of extra precaution in case
		// something got through the above filters
		$str = $this->_do_never_allowed($str);

		/*
		 * Images are Handled in a Special Way
		 * - Essentially, we want to know that after all of the character
		 * conversion is done whether any unwanted, likely XSS, code was found.
		 * If not, we return TRUE, as the image is clean.
		 * However, if the string post-conversion does not matched the
		 * string post-removal of XSS, then it fails, as there was unwanted XSS
		 * code found and removed/changed during processing.
		 */

		if ($is_image === TRUE) {
			return ($str == $converted_string)?TRUE:FALSE;
		}

		$this->write_log('debug', "XSS Filtering completed");

		return $str;
	}

	//-----文件和目录

	// 读文件函数
	public function read_file($file) {
		if (!file_exists($file)) {
			return FALSE;
		}

		if (function_exists('file_get_contents')) {
			return file_get_contents($file);
		}

		if (!$fp = @fopen($file, 'rb')) {
			return FALSE;
		}

		flock($fp, LOCK_SH);
		$data = '';

		if (filesize($file) > 0) {
			$data = &fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);
		return $data;
	}

	// 写文件函数
	public function write_file($path, $data, $mode = "rb+", $chmod = 0777) {
		@touch($path);

		if (!$this->is_really_writable($path)) {
			exit("<p>此文件不可写:<strong>{$path}</strong></p>");
		}

		if (!$handle = @fopen($path, $mode)) {
			return FALSE;
		}

		@flock($handle, LOCK_EX);
		@fwrite($handle, $data);

		if ($mode == "rb+") {
			@ftruncate($handle, strlen($data));
		}

		@flock($handle, LOCK_UN);
		@fclose($handle);
		@chmod($path, $chmod);

		return TRUE;
	}

	// 写日志函数
	public function write_log($level = 'error', $msg) {
		if ($this->log_threshold === 0) {
			return FALSE;
		}

		if ($this->log_enabled === FALSE) {
			return FALSE;
		}

		$level = strtoupper($level);

		if (!isset($this->log_levels[$level]) OR ($this->log_levels[$level] > (int) $this->log_threshold)) {
			return FALSE;
		}

		$filepath = $this->log_path.'/log-'.date('Y-m-d').'.php';
		$message  = '';

		if (!file_exists($filepath)) {
			$message .= "<"."?php if ( ! defined('MCDO')) exit('<h1>403</h1>'); ?".">\n\n";
		}

		$message .= $level.' '.(($level == 'INFO')?' -':'-').' '.date($this->log_date_format).' --> '.$msg."\n";

		return $this->write_file($filepath, $message, 'ab', 0666);
	}

	// 删除文件函数
	public function delete_files($path, $del_dir = FALSE, $level = 0) {
		// Trim the trailing slash
		$path = rtrim($path, DIRECTORY_SEPARATOR);

		if (!$current_dir = @opendir($path)) {
			return FALSE;
		}

		 while (FALSE !== ($filename = @readdir($current_dir))) {
			if ($filename != "." and $filename != "..") {
				if (is_dir($path.DIRECTORY_SEPARATOR.$filename)) {
					// Ignore empty folders
					if (substr($filename, 0, 1) != '.') {
						$this->delete_files($path.DIRECTORY_SEPARATOR.$filename, $del_dir, $level+1);
					}
				} else {
					unlink($path.DIRECTORY_SEPARATOR.$filename);
				}
			}
		}
		@closedir($current_dir);

		if ($del_dir == TRUE AND $level > 0) {
			return @rmdir($path);
		}

		return TRUE;
	}

	// 判断文件(夹)是否可写
	public function is_really_writable($file) {
		clearstatcache();

		// If we're on a Unix server with safe_mode off we call is_writable
		if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == FALSE) {
			return is_writable($file);
		}

		// For windows servers and safe_mode "on" installations we'll actually
		// write a file then read it.  Bah...
		if (is_dir($file)) {
			$file = rtrim($file, '/').'/'.md5(mt_rand(1, 100).mt_rand(1, 100));

			if (($fp = @fopen($file, 'ab')) === FALSE) {
				return FALSE;
			}

			fclose($fp);
			@chmod($file, 0777);
			@unlink($file);
			return TRUE;
		} elseif (!is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE) {
			return FALSE;
		}

		fclose($fp);
		return TRUE;
	}

	// 获取指定目录下所有文件名组成的数组
	public function get_filenames($source_dir, $include_path = FALSE, $_recursion = FALSE) {
		static $_filedata = array();

		if ($fp = @opendir($source_dir)) {
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE) {
				$_filedata  = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}

			 while (FALSE !== ($file = readdir($fp))) {
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0) {
					$this->get_filenames($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, TRUE);
				} elseif (strncmp($file, '.', 1) !== 0) {
					$_filedata[] = ($include_path == TRUE)?$source_dir.$file:$file;
				}
			}
			return $_filedata;
		} else {
			return FALSE;
		}
	}

	// 获取指定目录下的所有文件的文件名，文件大小，日期，文件权限等
	public function get_dir_file_info($source_dir, $top_level_only = TRUE, $_recursion = FALSE) {
		static $_filedata = array();
		$relative_path    = $source_dir;

		if ($fp = @opendir($source_dir)) {
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE) {
				$_filedata  = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}

			// foreach (scandir($source_dir, 1) as $file) // In addition to being PHP5+, scandir() is simply not as fast
			while (FALSE !== ($file = readdir($fp))) {
				if (@is_dir($source_dir.$file) AND strncmp($file, '.', 1) !== 0 AND $top_level_only === FALSE) {
					$this->get_dir_file_info($source_dir.$file.DIRECTORY_SEPARATOR, $top_level_only, TRUE);
				} elseif (strncmp($file, '.', 1) !== 0) {
					$_filedata[$file]                  = $this->get_file_info($source_dir.$file);
					$_filedata[$file]['relative_path'] = $relative_path;
				}
			}

			return $_filedata;
		} else {
			return FALSE;
		}
	}

	// 通过指定的路径和文件名，获取到指定的路径文件的文件名，文件大小，文件更改日期等
	public function get_file_info($file, $returned_values = array('name', 'server_path', 'size', 'date')) {
		if (!file_exists($file)) {
			return FALSE;
		}

		if (is_string($returned_values)) {
			$returned_values = explode(',', $returned_values);
		}

		foreach ($returned_values as $key) {
			switch ($key) {
				case 'name':
					$fileinfo['name'] = substr(strrchr($file, DIRECTORY_SEPARATOR), 1);
					break;
				case 'server_path':
					$fileinfo['server_path'] = $file;
					break;
				case 'size':
					$fileinfo['size'] = filesize($file);
					break;
				case 'date':
					$fileinfo['date'] = filemtime($file);
					break;
				case 'readable':
					$fileinfo['readable'] = is_readable($file);
					break;
				case 'writable':
					// There are known problems using is_weritable on IIS.  It may not be reliable - consider fileperms()
					$fileinfo['writable'] = is_writable($file);
					break;
				case 'executable':
					$fileinfo['executable'] = is_executable($file);
					break;
				case 'fileperms':
					$fileinfo['fileperms'] = fileperms($file);
					break;
			}
		}

		return $fileinfo;
	}

	// 按$mimes配置解释指定文件类型
	public function get_mime_by_extension($file) {
		$extension = strtolower(substr(strrchr($file, '.'), 1));

		if (array_key_exists($extension, $this->mimes)) {
			if (is_array($this->mimes[$extension])) {
				// Multiple mime types, just give the first one
				return current($this->mimes[$extension]);
			} else {
				return $this->mimes[$extension];
			}
		} else {
			return FALSE;
		}
	}

	// 目录遍历保护
	public function sanitize_filename($str, $relative_path = FALSE) {
		$bad = array(
			"../",
			"<!--",
			"-->",
			"<",
			">",
			"'",
			'"',
			'&',
			'$',
			'#',
			'{',
			'}',
			'[',
			']',
			'=',
			';',
			'?',
			"%20",
			"%22",
			"%3c", // <
			"%253c", // <
			"%3e", // >
			"%0e", // >
			"%28", // (
			"%29", // )
			"%2528", // (
			"%26", // &
			"%24", // $
			"%3f", // ?
			"%3b", // ;
			"%3d"// =
		);

		if (!$relative_path) {
			$bad[] = './';
			$bad[] = '/';
		}

		$str = $this->remove_invisible_characters($str, FALSE);
		return stripslashes(str_replace($bad, '', $str));
	}

	// 转换为符号权限
	public function symbolic_permissions($perms) {
		if (($perms&0xC000) == 0xC000) {
			$symbolic = 's';// Socket
		} elseif (($perms&0xA000) == 0xA000) {
			$symbolic = 'l';// Symbolic Link
		} elseif (($perms&0x8000) == 0x8000) {
			$symbolic = '-';// Regular
		} elseif (($perms&0x6000) == 0x6000) {
			$symbolic = 'b';// Block special
		} elseif (($perms&0x4000) == 0x4000) {
			$symbolic = 'd';// Directory
		} elseif (($perms&0x2000) == 0x2000) {
			$symbolic = 'c';// Character special
		} elseif (($perms&0x1000) == 0x1000) {
			$symbolic = 'p';// FIFO pipe
		} else {
			$symbolic = 'u';// Unknown
		}

		// Owner
		$symbolic .= (($perms&0x0100)?'r':'-');
		$symbolic .= (($perms&0x0080)?'w':'-');
		$symbolic .= (($perms&0x0040)?(($perms&0x0800)?'s':'x'):(($perms&0x0800)?'S':'-'));

		// Group
		$symbolic .= (($perms&0x0020)?'r':'-');
		$symbolic .= (($perms&0x0010)?'w':'-');
		$symbolic .= (($perms&0x0008)?(($perms&0x0400)?'s':'x'):(($perms&0x0400)?'S':'-'));

		// World
		$symbolic .= (($perms&0x0004)?'r':'-');
		$symbolic .= (($perms&0x0002)?'w':'-');
		$symbolic .= (($perms&0x0001)?(($perms&0x0200)?'t':'x'):(($perms&0x0200)?'T':'-'));

		return $symbolic;
	}

	// 转换为八进制权限
	public function octal_permissions($perms) {
		return substr(sprintf('%o', $perms), -3);
	}

	// 以数组形式列出指定目录所包含文件(夹)
	public function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE) {
		if ($fp = @opendir($source_dir)) {
			$filedata   = array();
			$new_depth  = $directory_depth-1;
			$source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

			while (FALSE !== ($file = readdir($fp))) {
				// Remove '.', '..', and hidden files [optional]
				if (!trim($file, '.') OR ($hidden == FALSE && $file[0] == '.')) {
					continue;
				}

				if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file)) {
					$filedata[$file] = $this->directory_map($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
				} else {
					$filedata[] = $file;
				}
			}

			closedir($fp);
			return $filedata;
		}

		return FALSE;
	}

	//-----URL

	// 转换url参数为数组
	public function convert_url_query($query) {
		$queryParts = explode('&', $query);
		$params     = array();
		foreach ($queryParts as $param) {
			$item             = explode('=', $param);
			$params[$item[0]] = $item[1];
		}
		return $params;
	}

	// 转换为友好的URL字串
	public function url_title($str, $separator = '-', $lowercase = TRUE) {
		if ($separator == 'dash') {
			$separator = '-';
		} else if ($separator == 'underscore') {
			$separator = '_';
		}

		$q_separator = preg_quote($separator);

		$trans = array(
			'&.+?;'               => '',
			'[^a-z0-9 _-]'        => '',
			'\s+'                 => $separator,
			'('.$q_separator.')+' => $separator
		);

		$str = strip_tags($str);

		foreach ($trans as $key => $val) {
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE) {
			$str = strtolower($str);
		}

		return trim($str, $separator);
	}

	// url跳转
	public function redirect($uri = '', $method = 'location', $http_response_code = 302) {
		if (!preg_match('#^https?://#i', $uri)) {
			$uri = $this->base_url($uri);
		}

		switch ($method) {
			case 'refresh':header("Refresh:0;url=".$uri);
				break;
			default:header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}

	// 生成url
	public function base_url($uri = '') {
		if ($uri == '') {
			return $this->base_url;
		} else {
			return $this->base_url.'/'.trim($uri, '/');
		}
	}

	// 缩短url
	public function short_url($url) {
		$url    = crc32($url);
		$result = sprintf("%u", $url);
		$sUrl   = '';
		while ($result > 0) {
			$s = $result%62;
			if ($s > 35) {
				$s = chr($s+61);
			} elseif ($s > 9 && $s <= 35) {
				$s = chr($s+55);
			}
			$sUrl .= $s;
			$result = floor($result/62);
		}
		return $sUrl;
	}

	// 将参数加入url
	public function join2url($strArr, $replace = '') {
		$params = $this->convert_url_query($_SERVER["QUERY_STRING"]);
		$url    = array_merge($params, $strArr);
		$_data  = array();
		ksort($url);
		reset($url);
		foreach ($url as $k => $v) {
			$_data[] = $k.'='.rawurlencode($v);
		}
		if ($replace != '') {
			unset($_data[$replace]);
			echo '?'.$replace.'&'.str_replace('&'.$replace.'=', '', implode('&', $_data));
		} else {
			echo '?'.implode('&', $_data);
		}
	}

	//-----字符串和数字

	// 对字符串进行hash加密
	public static function hash($string, $salt = NULL) {
		/** 生成随机字符串 */
		$salt   = empty($salt)?$this->random_string('alnum', 9):$salt;
		$length = strlen($string);
		$hash   = '';
		$last   = ord($string[$length-1]);
		$pos    = 0;

		/** 判断扰码长度 */
		if (strlen($salt) != 9) {
			/** 如果不是9直接返回 */
			return;
		}

		 while ($pos < $length) {
			$asc  = ord($string[$pos]);
			$last = ($last*ord($salt[($last%$asc)%9])+$asc)%95+32;
			$hash .= chr($last);
			$pos++;
		}

		return '$M_'.$salt.md5($hash);
	}

	// 判断hash值是否相等
	public static function hashValidate($from, $to) {
		if ('$M_' == substr($to, 0, 3)) {
			$salt = substr($to, 3, 9);
			return self::hash($from, $salt) == $to;
		} else {
			return md5($from) == $to;
		}
	}

	// 判断是否为ASCII
	public function is_ascii($str) {
		return (preg_match('/[^\x00-\x7F]/S', $str) == 0);
	}

	// 当执行一个循环时，让两个或两个以上的条目轮换使用
	public function alternator() {
		static $i;

		if (func_num_args() == 0) {
			$i = 0;
			return '';
		}
		$args = func_get_args();
		return $args[($i++%count($args))];
	}

	// 字符串截取
	public function character_limiter($str, $n = 500, $end_char = '&#8230;') {
		if (strlen($str) < $n) {
			return $str;
		}

		$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

		if (strlen($str) <= $n) {
			return $str;
		}

		$out = "";
		foreach (explode(' ', trim($str)) as $val) {
			$out .= $val.' ';

			if (strlen($out) >= $n) {
				$out = trim($out);
				return (strlen($out) == strlen($str))?$out:$out.$end_char;
			}
		}
	}

	// 转换字符串编码为UTF-8
	public function convert_to_utf8($str, $encoding) {
		if (function_exists('iconv')) {
			$str = @iconv($encoding, 'UTF-8', $str);
		} elseif (function_exists('mb_convert_encoding')) {
			$str = @mb_convert_encoding($str, 'UTF-8', $encoding);
		} else {
			return FALSE;
		}

		return $str;
	}

	// 确保字符串是UTF-8编码
	public function clean_string($str) {
		if ($this->is_ascii($str) === FALSE) {
			$str = @iconv('UTF-8', 'UTF-8//IGNORE', $str);
		}

		return $str;
	}

	// 过滤并截取字符串
	public function ellipsize($str, $max_length, $position = 1, $ellipsis = '&hellip;', $charset = 'UTF-8') {
		// Strip tags
		$str = trim(strip_tags($str));

		// Is the string long enough to ellipsize?
		if (mb_strlen($str, $charset) <= $max_length) {
			return $str;
		}

		$beg = mb_substr($str, 0, floor($max_length*$position), $charset);

		$position = ($position > 1)?1:$position;

		if ($position === 1) {
			$end = mb_substr($str, 0, -($max_length-mb_strlen($beg, $charset)), $charset);
		} else {
			$end = mb_substr($str, -($max_length-mb_strlen($beg, $charset)), $max_length, $charset);
		}

		return $beg.$ellipsis.$end;
	}

	// 高亮显示字符串短语
	public function highlight_phrase($str, $phrase, $tag_open = '<strong>', $tag_close = '</strong>') {
		if ($str == '') {
			return '';
		}

		if ($phrase != '') {
			return preg_replace('/('.preg_quote($phrase, '/').')/i', $tag_open."\\1".$tag_close, $str);
		}

		return $str;
	}

	// HTML转义
	public function html_escape($var) {
		if (is_array($var)) {
			return array_map('html_escape', $var);
		} else {
			return htmlspecialchars($var, ENT_QUOTES, $this->charset);
		}
	}

	// HTML实体解码
	public function entity_decode($str, $charset = 'UTF-8') {
		if (stristr($str, '&') === FALSE) {
			return $str;
		}

		$str = html_entity_decode($str, ENT_COMPAT, $charset);
		$str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
		return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
	}

	// 随机字符串
	public function random_string($type = 'alnum', $len = 8, $specialChars = FALSE) {
		switch ($type) {
			case 'basic':
				return mt_rand();
				break;
			case 'alpha':
			case 'alnum':
			case 'numeric':
			case 'nozero':
				switch ($type) {
				case 'alpha':
						$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
				case 'alnum':
						$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
				case 'numeric':
						$pool = '0123456789';
						break;
				case 'nozero':
						$pool = '123456789';
						break;
				}
				if ($specialChars) {
					$pool .= '!@#$%^&*()';
				}
				$str = '';
				for ($i = 0; $i < $len; $i++) {
					$str .= substr($pool, mt_rand(0, strlen($pool)-1), 1);
				}
				return $str;
				break;
			case 'md5unique':
				return md5(uniqid(mt_rand()));
				break;
			case 'sha1uniqid':
				return sha1(uniqid(mt_rand(), TRUE));
				break;
		}
	}

	// 删除不可见字符
	public function remove_invisible_characters($str, $url_encoded = TRUE) {
		$non_displayables = array();

		if ($url_encoded) {
			$non_displayables[] = '/%0[0-8bcef]/';// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';// url encoded 16-31
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';// 00-08, 11, 12, 14-31, 127

		do
		 {
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		} while ($count);

		return $str;
	}

	// 删除图片的一些不必要字符
	public function strip_image_tags($str) {
		$str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
		$str = preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);

		return $str;
	}

	// 过滤并替换字符串
	public function word_censor($str, $censored, $replacement = '') {
		if (!is_array($censored)) {
			return $str;
		}

		$str = ' '.$str.' ';

		// \w, \b and a few others do not match on a unicode character
		// set for performance reasons. As a result words like über
		// will not match on a word boundary. Instead, we'll assume that
		// a bad word will be bookeneded by any of these characters.
		$delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

		foreach ($censored as $badword) {
			if ($replacement != '') {
				$str = preg_replace("/({$delim})(" .str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/i", "\\1{$replacement}\\3", $str);
			} else {
				$str = preg_replace("/({$delim})(" .str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/ie", "'\\1'.str_repeat('#', strlen('\\2')).'\\3'", $str);
			}
		}

		return trim($str);
	}

	// 格式化为字节
	public function byte_format($num, $precision = 1) {
		if ($num >= 1000000000000) {
			$num  = round($num/1099511627776, $precision);
			$unit = 'TB';
		} elseif ($num >= 1000000000) {
			$num  = round($num/1073741824, $precision);
			$unit = 'GB';
		} elseif ($num >= 1000000) {
			$num  = round($num/1048576, $precision);
			$unit = 'MB';
		} elseif ($num >= 1000) {
			$num  = round($num/1024, $precision);
			$unit = 'KB';
		} else {
			$unit = 'Bytes';
			return number_format($num).' '.$unit;
		}

		return number_format($num, $precision).' '.$unit;
	}

	//-----表单

	// 格式化表单文本
	public function form_prep($str = '', $field_name = '') {
		static $prepped_fields = array();

		// if the field name is an array we do this recursively
		if (is_array($str)) {
			foreach ($str as $key => $val) {
				$str[$key] = $this->form_prep($val);
			}

			return $str;
		}

		if ($str === '') {
			return '';
		}

		// we've already prepped a field with this name
		// @todo need to figure out a way to namespace this so
		// that we know the *exact* field and not just one with
		// the same name
		if (isset($prepped_fields[$field_name])) {
			return $str;
		}

		$str = htmlspecialchars($str);

		// In case htmlspecialchars misses these.
		$str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);

		if ($field_name != '') {
			$prepped_fields[$field_name] = $field_name;
		}

		return $str;
	}

	// 设置表单的默认value
	public function set_value($field = '', $default = '') {
		if (!isset($_POST[$field])) {
			return $default;
		}
		return $this->form_prep($_POST[$field], $field);
	}

	// 设置表单的默认select
	public function set_select($field = '', $value = '', $default = FALSE) {
		if (!isset($_POST[$field])) {
			if (count($_POST) === 0 AND $default == TRUE) {
				return ' selected="selected"';
			}
			return '';
		}

		$field = $_POST[$field];

		if (is_array($field)) {
			if (!in_array($value, $field)) {
				return '';
			}
		} else {
			if (($field == '' OR $value == '') OR ($field != $value)) {
				return '';
			}
		}

		return ' selected="selected"';
	}

	// 设置表单的默认checkbox
	public function set_checkbox($field = '', $value = '', $default = FALSE) {
		if (!isset($_POST[$field])) {
			if (count($_POST) === 0 AND $default == TRUE) {
				return ' checked="checked"';
			}
			return '';
		}

		$field = $_POST[$field];

		if (is_array($field)) {
			if (!in_array($value, $field)) {
				return '';
			}
		} else {
			if (($field == '' OR $value == '') OR ($field != $value)) {
				return '';
			}
		}

		return ' checked="checked"';
	}

	//-----上传和下载

	// 将数据下载到桌面
	public function force_download($filename = '', $data = '') {
		if ($filename == '' OR $data == '') {
			return FALSE;
		}

		// Try to determine if the filename includes a file extension.
		// We need it in order to set the MIME type
		if (FALSE === strpos($filename, '.')) {
			return FALSE;
		}

		// Grab the file extension
		$x         = explode('.', $filename);
		$extension = end($x);

		// Set a default mime if we can't find it
		if (!isset($this->mimes[$extension])) {
			$mime = 'application/octet-stream';
		} else {
			$mime = (is_array($this->mimes[$extension]))?$this->mimes[$extension][0]:$this->mimes[$extension];
		}

		// Generate the server headers
		if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE) {
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header("Content-Transfer-Encoding: binary");
			header('Pragma: public');
			header("Content-Length: ".strlen($data));
		} else {
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Pragma: no-cache');
			header("Content-Length: ".strlen($data));
		}

		exit($data);
	}

	//-----时间

	// 返回当前的 Unix 时间戳
	public function now_unix() {
		if (strtolower($this->time_reference) == 'gmt') {
			$now         = time();
			$system_time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));

			if (strlen($system_time) < 10) {
				$system_time = time();
				$this->write_log('error', 'The Date class could not set a proper GMT timestamp so the local time() value was used.');
			}

			return $system_time;
		} else {
			return time();
		}
	}

	// 相对时间显示
	public function human_time_diff($from, $to = '') {
		if (empty($to)) {
			$to = time();
		}

		$diff = (int) abs($to-$from);

		if ($diff < 60*60) {
			$mins = round($diff/60);
			if ($mins <= 1) {
				$mins = 1;
			}

			$since = sprintf('%s 分', $mins);
		} elseif ($diff < 60*60*24 && $diff >= 60*60) {
			$hours = round($diff/60*60);
			if ($hours <= 1) {
				$hours = 1;
			}

			$since = sprintf('%s 小时', $hours);
		} elseif ($diff < 60*60*24*7 && $diff >= 60*60*24) {
			$days = round($diff/60*60*24);
			if ($days <= 1) {
				$days = 1;
			}

			$since = sprintf('%s 天', $days);
		} elseif ($diff < 30*60*60*24 && $diff >= 60*60*24*7) {
			$weeks = round($diff/60*60*24*7);
			if ($weeks <= 1) {
				$weeks = 1;
			}

			$since = sprintf('%s 周', $weeks);
		} elseif ($diff < 60*60*24*7*365 && $diff >= 30*60*60*24) {
			$months = round($diff/(30*60*60*24));
			if ($months <= 1) {
				$months = 1;
			}

			$since = sprintf('%s 月', $months);
		} elseif ($diff >= 60*60*24*7*365) {
			$years = round($diff/60*60*24*7*365);
			if ($years <= 1) {
				$years = 1;
			}

			$since = sprintf('%s 年', $years);
		}

		return $since.'前';
	}

	//-----缓存

	// 获取指定的缓存项
	public function get_cache_file($id, $use_memcached = FALSE, $metadata = FALSE) {

		if ($use_memcached AND $this->$memcached_enable) {
			$data = $this->memcached->get($id);

			return (is_array($data))?$data[0]:FALSE;
		}

		$cache_file = $this->cache_path.'/'.$id;

		if (!file_exists($cache_file)) {
			return FALSE;
		}

		$data = $this->read_file($cache_file);
		$data = unserialize($data);

		// 获取指定缓存项的详细信息
		if ($metadata) {

			if ($use_memcached AND $this->$memcached_enable) {
				$stored = $this->memcached->get($id);

				if (count($stored) !== 3) {
					return FALSE;
				}

				list($data, $time, $ttl) = $stored;

				return array(
					'expire' => $time+$ttl,
					'mtime'  => $time,
					'data'   => $data
				);
			}

			if (is_array($data)) {
				$mtime = filemtime($cache_file);

				if (!isset($data['ttl'])) {
					return FALSE;
				}

				return array(
					'expire' => $mtime+$data['ttl'],
					'mtime'  => $mtime
				);
			}

			return FALSE;
		}
		if (time() > $data['time']+$data['ttl']) {
			unlink($cache_file);
			return FALSE;
		}

		return $data['data'];
	}

	// 获取所有缓存信息
	public function get_cache_file_info($use_memcached = FALSE) {

		if ($use_memcached AND $this->$memcached_enable) {
			return $this->memcached->getStats();
		}

		return $this->get_dir_file_info($this->cache_path);
	}

	// 保存缓存文件
	public function save_cache_file($id, $data, $use_memcached = FALSE, $ttl = 60) {

		if ($use_memcached AND $this->$memcached_enable) {
			if (get_class($this->memcached) == 'Memcache') {
				return $this->memcached->set($id, array($data, time(), $ttl), 0, $ttl);
			} else if (get_class($this->memcached) == 'Memcached') {
				return $this->memcached->set($id, array($data, time(), $ttl), $ttl);
			}

			return FALSE;
		}

		$cache_file = $this->cache_path.'/'.$id;
		$contents   = array(
			'time' => time(),
			'ttl'  => $ttl,
			'data' => $data
		);

		if ($this->write_file($cache_file, serialize($contents))) {
			@chmod($cache_file, 0777);
			return TRUE;
		}

		return FALSE;
	}

	// 删除某个指定的缓存项
	public function delete_cache_file($id, $use_memcached = FALSE) {

		if ($use_memcached AND $this->$memcached_enable) {
			return $this->memcached->delete($id);
		}

		return unlink($this->cache_path.'/'.$id);
	}

	// 清空所有缓存
	public function clean_cache_file($use_memcached = FALSE) {

		if ($use_memcached AND $this->$memcached_enable) {
			return $this->memcached->flush();
		}

		return $this->delete_files($this->cache_path);
	}

	//-----User Agent

	// 获取当前浏览器UA
	public function user_agent() {
		return (!isset($_SERVER['HTTP_USER_AGENT']))?FALSE:$this->server('HTTP_USER_AGENT');
	}

	// 获取客户端系统语言
	public function get_agent_lang() {
		$accept_lang = $this->server('HTTP_ACCEPT_LANGUAGE');
		if ($accept_lang) {
			$lang = explode(',', $accept_lang);
			return $lang[0];
		}
		return 'Undefined';
	}

	// 获取来源url
	public function get_agent_referrer() {
		$referrer = $this->server('HTTP_REFERER');
		return empty($referrer)?'Direct access':$referrer;
	}

	// 获取客户端浏览器信息
	public function get_agent_browser($user_agent = '', $type = '') {
		$userAgent = empty($user_agent)?$this->user_agent():$user_agent;
		$platform  = NULL;
		$browser   = NULL;
		$version   = NULL;

		$empty = array('platform' => $platform, 'browser' => $browser, 'version' => $version);

		if (!$userAgent) {return $empty;
		}

		if (preg_match('/\((.*?)\)/im', $userAgent, $parent_matches)) {
			preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|iPhone|iPad|Linux|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|Nintendo\ (WiiU?|3DS)|Xbox(\ One)?)
                    (?:\ [^;]*)?
                    (?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);
			$priority           = array('Android', 'Xbox One', 'Xbox');
			$result['platform'] = array_unique($result['platform']);
			if (count($result['platform']) > 1) {
				if ($keys = array_intersect($priority, $result['platform'])) {
					$platform = reset($keys);
				} else {
					$platform = $result['platform'][0];
				}
			} elseif (isset($result['platform'][0])) {
				$platform = $result['platform'][0];
			}
		}

		if ($platform == 'linux-gnu') {
			$platform = 'Linux';
		} elseif ($platform == 'CrOS') {
			$platform = 'Chrome OS';
		}

		preg_match_all('%(?P<browser>Camino|Kindle(\ Fire\ Build)?|Firefox|Iceweasel|Safari|MSIE|Trident/.*rv|AppleWebKit|Chrome|IEMobile|Opera|OPR|Silk|Lynx|Midori|Version|Wget|curl|NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
                (?:\)?;?)
                (?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',
			$userAgent, $result, PREG_PATTERN_ORDER);

		// If nothing matched, return NULL (to avoid undefined index errors)
		if (!isset($result['browser'][0]) || !isset($result['version'][0])) {
			return;
		}

		$browser = $result['browser'][0];
		$version = $result['version'][0];

		$find = function ($search, &$key) use ($result) {
			$xkey = array_search(strtolower($search), array_map('strtolower', $result['browser']));
			if ($xkey !== FALSE) {
				$key = $xkey;
				return TRUE;
			}
			return FALSE;
		};

		$key = 0;
		if ($browser == 'Iceweasel') {
			$browser = 'Firefox';
		} elseif ($find('Playstation Vita', $key)) {
			$platform = 'PlayStation Vita';
			$browser  = 'Browser';
		} elseif ($find('Kindle Fire Build', $key) || $find('Silk', $key)) {
			$browser  = $result['browser'][$key] == 'Silk'?'Silk':'Kindle';
			$platform = 'Kindle Fire';
			if (!($version = $result['version'][$key]) || !is_numeric($version[0])) {
				$version = $result['version'][array_search('Version', $result['browser'])];
			}
		} elseif ($find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS') {
			$browser = 'NintendoBrowser';
			$version = $result['version'][$key];
		} elseif ($find('Kindle', $key)) {
			$browser  = $result['browser'][$key];
			$platform = 'Kindle';
			$version  = $result['version'][$key];
		} elseif ($find('OPR', $key)) {
			$browser = 'Opera Next';
			$version = $result['version'][$key];
		} elseif ($find('Opera', $key)) {
			$browser = 'Opera';
			$find('Version', $key);
			$version = $result['version'][$key];
		} elseif ($find('Midori', $key)) {
			$browser = 'Midori';
			$version = $result['version'][$key];
		} elseif ($find('Chrome', $key)) {
			$browser = 'Chrome';
			$version = $result['version'][$key];
		} elseif ($browser == 'AppleWebKit') {
			if (($platform == 'Android' && !($key = 0))) {
				$browser = 'Android Browser';
			} elseif (strpos($platform, 'BB') === 0) {
				$browser  = 'BlackBerry Browser';
				$platform = 'BlackBerry';
			} elseif ($platform == 'BlackBerry' || $platform == 'PlayBook') {
				$browser = 'BlackBerry Browser';
			} elseif ($find('Safari', $key)) {
				$browser = 'Safari';
			}

			$find('Version', $key);

			$version = $result['version'][$key];
		} elseif ($browser == 'MSIE' || strpos($browser, 'Trident') !== FALSE) {
			if ($find('IEMobile', $key)) {
				$browser = 'IEMobile';
			} else {
				$browser = 'MSIE';
				$key     = 0;
			}
			$version = $result['version'][$key];
		} elseif ($key = preg_grep('/playstation \d/i', array_map('strtolower', $result['browser']))) {
			$key = reset($key);

			$platform = 'PlayStation '.preg_replace('/[^\d]/i', '', $key);
			$browser  = 'NetFront';
		}

		switch ($type) {
			case 'platform':
				return $platform;
				break;
			case 'browser':
				return $browser;
				break;
			case 'version':
				return $version;
				break;
			case 'isKnown':
				return (empty($platform) && empty($browser) && empty($version));
				break;
			default:
				return array('platform' => $platform, 'browser' => $browser, 'version' => $version);
				break;
		}
	}

	// 获取当前用户的IP
	public function ip_address() {
		static $ip;
		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$ip = $this->server("HTTP_X_FORWARDED_FOR");
			} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$ip = $this->server("HTTP_CLIENT_IP");
			} else {
				$ip = $this->server("REMOTE_ADDR");
			}
		} else {
			if (getenv("HTTP_X_FORWARDED_FOR")) {
				$ip = getenv("HTTP_X_FORWARDED_FOR");
			} else if (getenv("HTTP_CLIENT_IP")) {
				$ip = getenv("HTTP_CLIENT_IP");
			} else {
				$ip = getenv("REMOTE_ADDR");
			}
		}

		if (strpos($ip, ',')) {
			$ips = array_reverse(explode(',', $ip));
			$ip  = $ips[0];
		}

		if (!$this->valid_ip($ip)) {
			$ip = '0.0.0.0';
		}

		return $ip;
	}

	//获取IP归属地
	public function ipHome($ip = '') {
		$ip     = empty($ip)?$this->ip_address():$ip;
		$time   = time();
		$ipdata = $this->base_path.'/mcdo/ipdata/'.$ip.'.json';

		if (!file_exists($ipdata) || filesize($ipdata) < 1) {
			$data = $this->http_get("http://opendata.baidu.com/api.php?query={$ip}&resource_id=6006&t={$time}&oe=utf8");
			$data = mb_convert_encoding($data, 'UTF-8', 'gbk');
			$info = json_decode($data, TRUE);
			$this->write_file($ipdata, serialize($info));
		} else {
			$info = unserialize($this->read_file($ipdata));
		}

		$location = empty($info['data'][0]['location'])?'未知':$info['data'][0]['location'];

		return $location;
	}

	// 判断是否为手持设备
	public function is_mobile() {
		$useragent = $this->user_agent();
		return preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));
	}

	// 判断是否为ajax请求
	public function is_ajax_request() {
		return ($this->server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest');
	}

	//-----数据获取

	// 通过get请求获得内容
	public function http_get($url) {
		$host = parse_url($url);
		$site = $host['scheme']."://".$host['host'];

		$ch = curl_init();

		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//禁用验证
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);// 30秒超时
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);// 返回原网页
		curl_setopt($ch, CURLOPT_REFERER, $site);// 伪造来源
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)');
		// 伪造User-Agent
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:1.2.4.8', 'X-FORWARDED-HOST:'.$host['host'], 'X-FORWARDED-SERVER:'.$host['host']));// 伪造HTTP头
		$content = curl_exec($ch);
		$status  = curl_getinfo($ch);
		curl_close($ch);

		if (intval($status["http_code"]) == 200) {
			return $content;
		} else {
			return FALSE;
		}
	}

	// 通过post请求获得内容
	public function http_post($url, $param) {
		$host = parse_url($url);
		$site = $host['scheme']."://".$host['host'];

		$ch = curl_init();

		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//禁用验证
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}

		if (is_string($param)) {
			$post = $param;
		} else {
			$pArr = array();
			foreach ($param as $key => $val) {
				$pArr[] = $key."=".urlencode($val);
			}
			$post = join("&", $pArr);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);// 30秒超时
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);// 返回原网页
		curl_setopt($ch, CURLOPT_REFERER, $site);// 伪造来源
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)');
		// 伪造User-Agent
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:1.2.4.8', 'X-FORWARDED-HOST:'.$host['host'], 'X-FORWARDED-SERVER:'.$host['host']));// 伪造HTTP头
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$content = curl_exec($ch);
		$status  = curl_getinfo($ch);
		curl_close($ch);

		if (intval($status["http_code"]) == 200) {
			return $content;
		} else {
			return FALSE;
		}
	}

	//-----分页

	// 分页函数
	public function page_nav($maxnum, $max_page, $paged, $p = 5) {
		if (empty($paged)) {
			$paged = 1;
		}
		echo "<div class=\"pagefind\">找到 {$maxnum} 条相关数据</div>\n";
		if ($max_page == 1) {
			echo '<div class="pagination"><span class="page-numbers current">1</span></div>';
		} else {
			echo "<div class=\"pagination\">\n";
			if ($paged > 1) {
				$this->_page_link($paged-1, '上页', '上页', 'page_previous', 'prev');
			}
			if ($paged > $p+1) {
				$this->_page_link(1, '首页', '', '', 'first');
			}
			if ($paged > $p+2) {
				echo '<span class="page-numbers">···</span>';
			}
			for ($i = $paged-$p; $i <= $paged+$p; $i++) {
				if ($i > 0 && $i <= $max_page) {
					$i == $paged?print"<span class=\"page-numbers current\">{$i}</span> ":$this->_page_link($i);
				}
			}
			if ($paged < $max_page-$p-1) {
				echo '<span class="page-numbers">···</span>';
			}
			if ($paged < $max_page-$p) {
				$this->_page_link($max_page, '末页', '', '', 'last');
			}
			if ($paged < $max_page) {
				$this->_page_link($paged+1, '下页', '下页', 'page_next', 'next');
			}
			echo "</div>\n";
		}
	}

	/*-----私有函数---------------------------------------*/

	// 清理输入数据
	private function _clean_input_data($str) {
		if (is_array($str)) {
			$new_array = array();
			foreach ($str as $key => $val) {
				$new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
			return $new_array;
		}

		/* We strip slashes if magic quotes is on to keep things consistent

		NOTE: In PHP 5.4 get_magic_quotes_gpc() will always return 0 and
		it will probably not exist in future versions at all.
		 */
		if (!$this->is_php('5.4') && get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}

		// Clean UTF-8 if supported
		if (UTF8_ENABLED === TRUE) {
			$str = $this->clean_string($str);
		}

		// Remove control characters
		$str = $this->remove_invisible_characters($str);

		// Should we filter the input data?
		$str = $this->xss_clean($str);

		// Standardize newlines if needed

		if (strpos($str, "\r") !== FALSE) {
			$str = str_replace(array("\r\n", "\r", "\r\n\n"), PHP_EOL, $str);
		}

		return $str;
	}

	// 清理键值 只能为数字字母和:_\/-
	private function _clean_input_keys($str) {
		if (!preg_match("/^[a-z0-9:_\/-]+$/i", $str)) {
			exit('Disallowed Key Characters.');
		}

		// Clean UTF-8 if supported
		if (UTF8_ENABLED === TRUE) {
			$str = $this->clean_string($str);
		}

		return $str;
	}

	// 从全局数组中检索值
	private function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE) {
		if (!isset($array[$index])) {
			return FALSE;
		}

		if ($xss_clean === TRUE) {
			return $this->xss_clean($array[$index]);
		}

		return $array[$index];
	}

	// 净化全局变量
	private function _sanitize_globals() {
		// It would be "wrong" to unset any of these GLOBALS.
		$protected = array('_SERVER', '_GET', '_POST', '_FILES', '_REQUEST',
			'_SESSION', '_ENV', 'GLOBALS', 'HTTP_RAW_POST_DATA',
			'system_folder', 'application_folder', 'BM', 'EXT',
			'CFG', 'URI', 'RTR', 'OUT', 'IN');

		// Unset globals for security.
		// This is effectively the same as register_globals = off
		foreach (array($_GET, $_POST, $_COOKIE) as $global) {
			if (!is_array($global)) {
				if (!in_array($global, $protected)) {
					global $$global;
					$$global = NULL;
				}
			} else {
				foreach ($global as $key => $val) {
					if (!in_array($key, $protected)) {
						global $$key;
						$$key = NULL;
					}
				}
			}
		}

		if (is_array($_GET) AND count($_GET) > 0) {
			foreach ($_GET as $key => $val) {
				$_GET[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
		}

		// Clean $_POST Data
		if (is_array($_POST) AND count($_POST) > 0) {
			foreach ($_POST as $key => $val) {
				$_POST[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
		}

		// Clean $_COOKIE Data
		if (is_array($_COOKIE) AND count($_COOKIE) > 0) {
			// Also get rid of specially treated cookies that might be set by a server
			// or silly application, that are of no use to a CI application anyway
			// but that when present will trip our 'Disallowed Key Characters' alarm
			// http://www.ietf.org/rfc/rfc2109.txt
			// note that the key names below are single quoted strings, and are not PHP variables
			unset($_COOKIE['$Version']);
			unset($_COOKIE['$Path']);
			unset($_COOKIE['$Domain']);

			foreach ($_COOKIE as $key => $val) {
				$_COOKIE[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
		}

		// Sanitize PHP_SELF
		$_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);

		// CSRF Protection check on HTTP requests
		$this->csrf_verify();

		$this->write_log('debug', "Global POST and COOKIE data sanitized");
	}

	// 配置 memcached
	private function _setup_memcached() {
		// pecl扩展库版本
		if (extension_loaded('memcache')) {
			$this->memcached = new Memcache();
			$this->memcached->connect($this->memcached_host, $this->memcached_port);

			// libmemcached版本
		} else if (extension_loaded('memcached')) {
			$this->memcached = new Memcached();
			$this->memcached->addServer($this->memcached_host, $this->memcached_port, $this->memcached_persistent, $this->memcached_weight);
		}
	}

	/*-----保护函数---------------------------------------*/

	// 属性转换
	protected function _convert_attribute($match) {
		return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
	}

	// HTML实体解码回调
	protected function _decode_entity($match) {
		return $this->entity_decode($match[0], strtoupper($this->charset));
	}

	// 去除不允许字符
	protected function _do_never_allowed($str) {
		$str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);

		foreach ($this->_never_allowed_regex as $regex) {
			$str = preg_replace('#'.$regex.'#is', '[removed]', $str);
		}

		return $str;
	}

	// 去除空格
	protected function _compact_exploded_words($matches) {
		return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
	}

	// 过滤属性
	protected function _filter_attributes($str) {
		$out = '';

		if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches)) {
			foreach ($matches[0] as $match) {
				$out .= preg_replace("#/\*.*?\*/#s", '', $match);
			}
		}

		return $out;
	}

	// 设置跨站点请求伪造Cookie的保护
	protected function _csrf_set_hash() {
		if ($this->csrf_hash == '') {
			// If the cookie exists we will use it's value.
			// We don't necessarily want to regenerate it with
			// each page load since a page could contain embedded
			// sub-pages causing this feature to fail
			if (isset($_COOKIE[$this->csrf_cookie_name]) &&
				preg_match('#^[0-9a-f]{32}$#iS', $_COOKIE[$this->csrf_cookie_name]) === 1) {
				return $this->csrf_hash = $_COOKIE[$this->csrf_cookie_name];
			}

			return $this->csrf_hash = md5(uniqid(rand(), TRUE));
		}

		return $this->csrf_hash;
	}

	// 移除图片链接
	protected function _js_img_removal($match) {
		return str_replace(
			$match[1],
			preg_replace(
				'#src=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si',
				'',
				$this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]))
			),
			$match[0]
		);
	}

	// 移除JS链接
	protected function _js_link_removal($match) {
		return str_replace(
			$match[1],
			preg_replace(
				'#href=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|data\s*:)#si',
				'',
				$this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]))
			),
			$match[0]
		);
	}

	// 生成分页链接函数
	protected function _page_link($i, $title = '', $linktype = '', $class = 'page-numbers', $rel = '') {
		if ($title == '') {
			$title = "第 $i 页";
			$rel   = 'next" rev="prev';
		}
		$linktext = ($linktype == '')?$i:$linktype;
		echo '<a class="'.$class.'" href="';
		$this->joinurl(array('page' => $i));
		echo '" title="'.$title.'" rel="'.$rel.'">'.$linktext.'</a> ';
	}

	// 清除多余的HTML属性（如事件处理和风格）
	protected function _remove_evil_attributes($str, $is_image) {
		// All javascript event handlers (e.g. onload, onclick, onmouseover), style, and xmlns
		$evil_attributes = array('on\w*', 'style', 'xmlns', 'formaction');

		if ($is_image === TRUE) {
			/*
			 * Adobe Photoshop puts XML metadata into JFIF images,
			 * including namespacing, so we have to allow this for images.
			 */
			unset($evil_attributes[array_search('xmlns', $evil_attributes)]);
		}

		do {
			$count   = 0;
			$attribs = array();

			// find occurrences of illegal attribute strings with quotes (042 and 047 are octal quotes)
			preg_match_all('/('.implode('|', $evil_attributes).')\s*=\s*(\042|\047)([^\\2]*?)(\\2)/is', $str, $matches, PREG_SET_ORDER);

			foreach ($matches as $attr) {
				$attribs[] = preg_quote($attr[0], '/');
			}

			// find occurrences of illegal attribute strings without quotes
			preg_match_all('/('.implode('|', $evil_attributes).')\s*=\s*([^\s>]*)/is', $str, $matches, PREG_SET_ORDER);

			foreach ($matches as $attr) {
				$attribs[] = preg_quote($attr[0], '/');
			}

			// replace illegal attribute strings that are inside an html tag
			if (count($attribs) > 0) {
				$str = preg_replace('/(<?)(\/?[^><]+?)([^A-Za-z<>\-])(.*?)('.implode('|', $attribs).')(.*?)([\s><]?)([><]*)/i', '$1$2 $4$6$7$8', $str, -1, $count);
			}

		} while ($count);

		return $str;
	}

	// 净化HTML
	protected function _sanitize_naughty_html($matches) {
		// encode opening brace
		$str = '&lt;'.$matches[1].$matches[2].$matches[3];

		// encode captured opening or closing brace to prevent recursive vectors
		$str .= str_replace(array('>', '<'), array('&gt;', '&lt;'),
			$matches[4]);

		return $str;
	}

	// 验证URL实体
	protected function _validate_entities($str) {
		/*
		 * Protect GET variables in URLs
		 */

		// 901119URL5918AMP18930PROTECT8198

		$str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', $this->_xss_set_hash()."\\1=\\2", $str);

		/*
		 * Validate standard character entities
		 *
		 * Add a semicolon if missing.  We do this to enable
		 * the conversion of entities to ASCII later.
		 *
		 */
		$str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);

		/*
		 * Validate UTF16 two byte encoding (x00)
		 *
		 * Just as above, adds a semicolon if missing.
		 *
		 */
		$str = preg_replace('#(&\#x?)([0-9A-F]+);?#i', "\\1\\2;", $str);

		/*
		 * Un-Protect GET variables in URLs
		 */
		$str = str_replace($this->_xss_set_hash(), '&', $str);

		return $str;
	}

	// 验证是否为IPv4地址
	protected function _valid_ipv4($ip) {
		$ip_segments = explode('.', $ip);

		// Always 4 segments needed
		if (count($ip_segments) !== 4) {
			return FALSE;
		}
		// IP can not start with 0
		if ($ip_segments[0][0] == '0') {
			return FALSE;
		}

		// Check each segment
		foreach ($ip_segments as $segment) {
			// IP segments must be digits and can not be
			// longer than 3 digits or greater then 255
			if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3) {
				return FALSE;
			}
		}

		return TRUE;
	}

	// 验证是否为IPv6地址
	protected function _valid_ipv6($str) {
		// 8 groups, separated by :
		// 0-ffff per group
		// one set of consecutive 0 groups can be collapsed to ::

		$groups    = 8;
		$collapsed = FALSE;

		$chunks = array_filter(
			preg_split('/(:{1,2})/', $str, NULL, PREG_SPLIT_DELIM_CAPTURE)
		);

		// Rule out easy nonsense
		if (current($chunks) == ':' OR end($chunks) == ':') {
			return FALSE;
		}

		// PHP supports IPv4-mapped IPv6 addresses, so we'll expect those as well
		if (strpos(end($chunks), '.') !== FALSE) {
			$ipv4 = array_pop($chunks);

			if (!$this->_valid_ipv4($ipv4)) {
				return FALSE;
			}

			$groups--;
		}

		 while ($seg = array_pop($chunks)) {
			if ($seg[0] == ':') {
				if (--$groups == 0) {
					return FALSE;// too many groups
				}

				if (strlen($seg) > 2) {
					return FALSE;// long separator
				}

				if ($seg == '::') {
					if ($collapsed) {
						return FALSE;// multiple collapsed
					}

					$collapsed = TRUE;
				}
			} elseif (preg_match("/[^0-9a-f]/i", $seg) OR strlen($seg) > 4) {
				return FALSE;// invalid segment
			}
		}

		return $collapsed OR $groups == 1;
	}

	// 随机Hash保护
	protected function _xss_set_hash() {
		if ($this->xss_hash == '') {
			mt_srand();
			$this->xss_hash = md5(time()+mt_rand(0, 1999999999));
		}

		return $this->xss_hash;
	}

	//-----数据库相关

	protected function db_column_quote($string) {
		return '"'.str_replace('.', '"."', preg_replace('/(^#|\(JSON\))/', '', $string)).'"';
	}

	protected function db_column_push($columns) {
		if ($columns == '*') {
			return $columns;
		}

		if (is_string($columns)) {
			$columns = array($columns);
		}

		$stack = array();

		foreach ($columns as $key => $value) {
			preg_match('/([a-zA-Z0-9_\-\.]*)\s*\(([a-zA-Z0-9_\-]*)\)/i', $value, $match);

			if (isset($match[1], $match[2])) {
				array_push($stack, $this->db_column_quote($match[1]).' AS '.$this->db_column_quote($match[2]));
			} else {
				array_push($stack, $this->db_column_quote($value));
			}
		}

		return implode($stack, ',');
	}

	protected function db_array_quote($array) {
		$temp = array();

		foreach ($array as $value) {
			$temp[] = is_int($value)?$value:$this->db_pdo->quote($value);
		}

		return implode($temp, ',');
	}

	protected function db_inner_conjunct($data, $conjunctor, $outer_conjunctor) {
		$haystack = array();

		foreach ($data as $value) {
			$haystack[] = '('.$this->db_data_implode($value, $conjunctor).')';
		}

		return implode($outer_conjunctor.' ', $haystack);
	}

	protected function db_fn_quote($column, $string) {
		return (strpos($column, '#') === 0 && preg_match('/^[A-Z0-9\_]*\([^)]*\)$/', $string))?

		$string:

		$this->db_quote($string);
	}

	protected function db_data_implode($data, $conjunctor, $outer_conjunctor = NULL) {
		$wheres = array();

		foreach ($data as $key => $value) {
			$type = gettype($value);

			if (
				preg_match("/^(AND|OR)\s*#?/i", $key, $relation_match) &&
				$type == 'array'
			) {
				$wheres[] = 0 !== count(array_diff_key($value, array_keys(array_keys($value))))?
				'('.$this->db_data_implode($value, ' '.$relation_match[1]).')':
				'('.$this->db_inner_conjunct($value, ' '.$relation_match[1], $conjunctor).')';
			} else {
				preg_match('/(#?)([\w\.]+)(\[(\>|\>\=|\<|\<\=|\!|\<\>|\>\<)\])?/i', $key, $match);
				$column = $this->db_column_quote($match[2]);

				if (isset($match[4])) {
					if ($match[4] == '') {
						$wheres[] = $column.' '.$match[4].'= '.$this->db_quote($value);
					} elseif ($match[4] == '!') {
						switch ($type) {
							case 'NULL':
								$wheres[] = $column.' IS NOT NULL';
								break;

							case 'array':
								$wheres[] = $column.' NOT IN ('.$this->db_array_quote($value).')';
								break;

							case 'integer':
							case 'double':
								$wheres[] = $column.' != '.$value;
								break;

							case 'boolean':
								$wheres[] = $column.' != '.($value?'1':'0');
								break;

							case 'string':
								$wheres[] = $column.' != '.$this->db_fn_quote($key, $value);
								break;
						}
					} else {
						if ($match[4] == '<>' || $match[4] == '><') {
							if ($type == 'array') {
								if ($match[4] == '><') {
									$column .= ' NOT';
								}

								if (is_numeric($value[0]) && is_numeric($value[1])) {
									$wheres[] = '('.$column.' BETWEEN '.$value[0].' AND '.$value[1].')';
								} else {
									$wheres[] = '('.$column.' BETWEEN '.$this->db_quote($value[0]).' AND '.$this->db_quote($value[1]).')';
								}
							}
						} else {
							if (is_numeric($value)) {
								$wheres[] = $column.' '.$match[4].' '.$value;
							} else {
								$datetime = strtotime($value);

								if ($datetime) {
									$wheres[] = $column.' '.$match[4].' '.$this->db_quote(date('Y-m-d H:i:s', $datetime));
								}
							}
						}
					}
				} else {
					if (is_int($key)) {
						$wheres[] = $this->db_quote($value);
					} else {
						switch ($type) {
							case 'NULL':
								$wheres[] = $column.' IS NULL';
								break;

							case 'array':
								$wheres[] = $column.' IN ('.$this->db_array_quote($value).')';
								break;

							case 'integer':
							case 'double':
								$wheres[] = $column.' = '.$value;
								break;

							case 'boolean':
								$wheres[] = $column.' = '.($value?'1':'0');
								break;

							case 'string':
								$wheres[] = $column.' = '.$this->db_fn_quote($key, $value);
								break;
						}
					}
				}
			}
		}

		return implode($conjunctor.' ', $wheres);
	}

	protected function db_where_clause($where) {
		$where_clause = '';

		if (is_array($where)) {
			$where_keys = array_keys($where);
			$where_AND  = preg_grep("/^AND\s*#?$/i", $where_keys);
			$where_OR   = preg_grep("/^OR\s*#?$/i", $where_keys);

			$single_condition = array_diff_key($where, array_flip(
					explode(' ', 'AND OR GROUP ORDER HAVING LIMIT LIKE MATCH')
				));

			if ($single_condition != array()) {
				$where_clause = ' WHERE '.$this->db_data_implode($single_condition, '');
			}

			if (!empty($where_AND)) {
				$value        = array_values($where_AND);
				$where_clause = ' WHERE '.$this->db_data_implode($where[$value[0]], ' AND');
			}

			if (!empty($where_OR)) {
				$value        = array_values($where_OR);
				$where_clause = ' WHERE '.$this->db_data_implode($where[$value[0]], ' OR');
			}

			if (isset($where['LIKE'])) {
				$like_query = $where['LIKE'];

				if (is_array($like_query)) {
					$is_OR       = isset($like_query['OR']);
					$clause_wrap = array();

					if ($is_OR || isset($like_query['AND'])) {
						$connector  = $is_OR?'OR':'AND';
						$like_query = $is_OR?$like_query['OR']:$like_query['AND'];
					} else {
						$connector = 'AND';
					}

					foreach ($like_query as $column => $keyword) {
						$keyword = is_array($keyword)?$keyword:array($keyword);

						foreach ($keyword as $key) {
							preg_match('/(%?)([a-zA-Z0-9_\-\.]*)(%?)((\[!\])?)/', $column, $column_match);

							if ($column_match[1] == '' && $column_match[3] == '') {
								$column_match[1] = '%';
								$column_match[3] = '%';
							}

							$clause_wrap[] =
							$this->db_column_quote($column_match[2]).
							($column_match[4] != ''?' NOT':'').' LIKE '.
							$this->db_quote($column_match[1].$key.$column_match[3]);
						}
					}

					$where_clause .= ($where_clause != ''?' AND ':' WHERE ').'('.implode($clause_wrap, ' '.$connector.' ').')';
				}
			}

			if (isset($where['MATCH'])) {
				$match_query = $where['MATCH'];

				if (is_array($match_query) && isset($match_query['columns'], $match_query['keyword'])) {
					$where_clause .= ($where_clause != ''?' AND ':' WHERE ').' MATCH ("'.str_replace('.', '"."', implode($match_query['columns'], '", "')).'") AGAINST ('.$this->db_quote($match_query['keyword']).')';
				}
			}

			if (isset($where['GROUP'])) {
				$where_clause .= ' GROUP BY '.$this->db_column_quote($where['GROUP']);
			}

			if (isset($where['ORDER'])) {
				$rsort = '/(^[a-zA-Z0-9_\-\.]*)(\s*(DESC|ASC))?/';

				if (is_array($where['ORDER'])) {
					if (
						isset($where['ORDER'][1]) &&
						is_array($where['ORDER'][1])
					) {
						$where_clause .= ' ORDER BY FIELD('.$this->db_column_quote($where['ORDER'][0]).', '.$this->db_array_quote($where['ORDER'][1]).')';
					} else {
						$stack = array();

						foreach ($where['ORDER'] as $column) {
							preg_match($rsort, $column, $order_match);

							array_push($stack, '"'.str_replace('.', '"."', $order_match[1]).'"'.(isset($order_match[3])?' '.$order_match[3]:''));
						}

						$where_clause .= ' ORDER BY '.implode($stack, ',');
					}
				} else {
					preg_match($rsort, $where['ORDER'], $order_match);

					$where_clause .= ' ORDER BY "'.str_replace('.', '"."', $order_match[1]).'"'.(isset($order_match[3])?' '.$order_match[3]:'');
				}

				if (isset($where['HAVING'])) {
					$where_clause .= ' HAVING '.$this->db_data_implode($where['HAVING'], '');
				}
			}

			if (isset($where['LIMIT'])) {
				if (is_numeric($where['LIMIT'])) {
					$where_clause .= ' LIMIT '.$where['LIMIT'];
				}

				if (
					is_array($where['LIMIT']) &&
					is_numeric($where['LIMIT'][0]) &&
					is_numeric($where['LIMIT'][1])
				) {
					$where_clause .= ' LIMIT '.$where['LIMIT'][0].','.$where['LIMIT'][1];
				}
			}
		} else {
			if ($where != NULL) {
				$where_clause .= ' '.$where;
			}
		}

		return $where_clause;
	}

	protected function db_select_context($table, $join, &$columns = NULL, $where = NULL, $column_fn = NULL) {
		$table    = '"'.$table.'"';
		$join_key = is_array($join)?array_keys($join):NULL;

		if (
			isset($join_key[0]) &&
			strpos($join_key[0], '[') === 0
		) {
			$table_join = array();

			$join_array = array(
				'>'  => 'LEFT',
				'<'  => 'RIGHT',
				'<>' => 'FULL',
				'><' => 'INNER'
			);

			foreach ($join as $sub_table => $relation) {
				preg_match('/(\[(\<|\>|\>\<|\<\>)\])?([a-zA-Z0-9_\-]*)/', $sub_table, $match);

				if ($match[2] != '' && $match[3] != '') {
					if (is_string($relation)) {
						$relation = 'USING ("'.$relation.'")';
					}

					if (is_array($relation)) {
						// For ['column1', 'column2']
						if (isset($relation[0])) {
							$relation = 'USING ("'.implode($relation, '", "').'")';
						}
						// For ['column1' => 'column2']
						else{
							$relation = 'ON '.$table.'."'.key($relation).'" = "'.$match[3].'"."'.current($relation).'"';
						}
					}

					$table_join[] = $join_array[$match[2]].' JOIN "'.$match[3].'" '.$relation;
				}
			}

			$table .= ' '.implode($table_join, ' ');
		} else {
			if (is_null($columns)) {
				if (is_null($where)) {
					if (
						is_array($join) &&
						isset($column_fn)
					) {
						$where   = $join;
						$columns = NULL;
					} else {
						$where   = NULL;
						$columns = $join;
					}
				} else {
					$where   = $join;
					$columns = NULL;
				}
			} else {
				$where   = $columns;
				$columns = $join;
			}
		}

		if (isset($column_fn)) {
			if ($column_fn == 1) {
				$column = '1';

				if (is_null($where)) {
					$where = $columns;
				} else {
					$where = $join;
				}
			} else {
				if (empty($columns)) {
					$columns = '*';
					$where   = $join;
				}

				$column = $column_fn.'('.$this->db_column_push($columns).')';
			}
		} else {
			$column = $this->db_column_push($columns);
		}

		return 'SELECT '.$column.' FROM '.$table.$this->db_where_clause($where);
	}

}

/*********************** end code ***/