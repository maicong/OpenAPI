`yum install subversion -y`

`mkdir -p /var/svn/project`

`svnadmin create /var/svn/project`

`cp /var/svn/project/hooks/post-commit.tmpl /var/svn/project/hooks/post-commit`

```
#!/bin/sh

export LANG=zh_CN.UTF-8

REPOS="$1"
REV="$2"
SVN_PATH=/usr/bin/svn
WEB_PATH=/home/wwwroot/project
LOG_PATH=/tmp/svn_update.log
echo "nnn##########开始提交 " `date "+%Y-%m-%d %H:%M:%S"` '##################' >> $LOG_PATH
echo `whoami`,$REPOS,$REV >> $LOG_PATH
$SVN_PATH checkout svn://localhost/project $WEB_PATH --username "username" --password "password" >> $LOG_PATH
chown -R www:www $WEB_PATH
```

`systemctl start svnserve`
