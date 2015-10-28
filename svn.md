`yum install subversion -y`

`mkdir -p /var/svn/repos/project`

`svnadmin create /var/svn/repos/project`

`cp /var/svn/repos/project/hooks/post-commit.tmpl /var/svn/repos/project/hooks/post-commit`

```
#!/bin/sh

export LANG=zh_CN.UTF-8

REPOS="$1"
REV="$2"
WEB_PATH=/home/wwwroot/project
LOG_PATH=/tmp/svn_update.log
SVN_PATH=/usr/bin/svn
SVN_REPOS=svn://localhost/repos/project
SVN_USER="username";
SVN_PWD="password";

echo "nnn##########开始提交 " `date "+%Y-%m-%d %H:%M:%S"` '##################' >> $LOG_PATH
echo `whoami`,$REPOS,$REV >> $LOG_PATH
echo `$SVN_PATH checkout $SVN_REPOS $WEB_PATH --username $SVN_USER --password $SVN_PWD --no-auth-cache >> $LOG_PATH`
chown -R www:www $WEB_PATH

```

`systemctl start svnserve`
