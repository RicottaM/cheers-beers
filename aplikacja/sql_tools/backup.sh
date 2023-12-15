#!/bin/bash
mysqldump -h mysql -u root -pprestashop prestashop > /var/lib/mysql/db_backup/backup.sql
