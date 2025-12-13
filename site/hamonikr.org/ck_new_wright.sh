#!/bin/bash

log="/var/log/ck_new_wright.log"
admin_email="hji@invesume.com"
admin_email1="bdh1993@invesume.com"

uid='hamonitr'
upw='gkahslzk!$('

CK_TIME_FIRST="`/bin/date -d '1 hour ago' +%Y%m%d%H`"
CK_TIME_END="`/bin/date +%Y%m%d%H`"

POSTS_QUERY="select title, nick_name, last_update from xe_documents"
#COMMENTS_QUERY="select document_srl, content, nick_name from xe_comments"
WHERE_QUERY=" WHERE last_update BETWEEN '$CK_TIME_FIRST' AND '$CK_TIME_END' order by last_update desc;"

MSG=""

# check new post
POSTS_QUERY="$POSTS_QUERY $WHERE_QUERY"

# not cron
#CONTENT=`/usr/bin/mysql --login-path=hamonitr jaycedb -e "$POSTS_QUERY"`

# use cron
CONTENT=`/usr/bin/mysql -hlocalhost -u$uid -p$upw jaycedb -e "$POSTS_QUERY"`

if [ "" != "$CONTENT" ]
then
	MSG+="###### New Posts ######\n\n"
	MSG+="$CONTENT+\n\n\n\n"
fi


## check new comments
#COMMENTS_QUERY="$COMMENTS_QUERY $WHERE_QUERY"
#
## not cron
#CONTENT=`/usr/bin/mysql --login-path=hamonitr jaycedb -e "$COMMENTS_QUERY"`
#
## use cron
#CONTENT=`/usr/bin/mysql -hlocalhost -u$uid -p$upw jaycedb -e "$COMMENTS_QUERY"`
#
#if [ "" != "$CONTENT" ]
#then
#	MSG+="###### New Comments ######\n\n"
#	MSG+="$CONTENT"
#fi


# send mail
if [ "" != "$MSG" ]
then
	SUBJECT="[hamonikr.org] New infomation"
	/bin/echo -e "Subject: $SUBJECT \n\n$MSG" > content
	/usr/sbin/sendmail $admin_email < content
	/usr/sbin/sendmail $admin_email1 < content
fi
