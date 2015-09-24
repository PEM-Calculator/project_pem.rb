#!/bin/bash
APP_PATH=$(pwd)
cd ../Rainbow
DIR=$(pwd)
cd $APP_PATH

FILES=(
	app/config/loader.php
	app/config/services.php
	app/config/translate.ini
	app/Rainbow
	app/views/admin
	app/controllers/ErrorsController.php
	app/views/errors
	app/views/PageGenerator
	public/index.php
	public/update.php
	public/test_style.php
	public/test_models.php
	public/fonts
	public/css/less.php
	public/css/admin.less
	public/css/flaticons.less
	public/css/getcss.php
	public/css/.htaccess
	public/js/admin.js
	public/js/bootstrap.min.js
	public/js/getjs.php
	public/js/jquery.lionbars.0.3.js
	public/js/jquery-1.10.2.min.js
	public/js/jquery-2.1.4.min.js
	public/js/jquery-ui1.8.5.min.js
	public/js/JSMin.php
	public/js/nunjucks.min.js
	public/js/PageGenerator.js
	public/js/templates.js
	public/js/templates.php
	public/js/utils.js
	public/js/.htaccess
)

for i in ${FILES[@]}; do
	# check file/dir
	if [ ! -f $DIR/${i} ] && [ ! -d $DIR/${i} ]; then
		echo "Not exists $DIR/${i}"
		exit 1
	fi
	# rm
	if [ -L ${i} ]; then
		echo "Del ${i}"
		rm ${i}
		CODE=$?
		if [ $CODE -ne 0 ]; then
			echo "Exit with $CODE"
			exit $CODE
		fi
	fi
	# ln
	echo "$DIR/${i} --> ${i}"
	ln -s $DIR/${i} ${i}
	CODE=$?
	if [ $CODE -ne 0 ]; then
		echo "Exit with $CODE"
		exit $CODE
	fi
done

echo "Ok"
exit 0