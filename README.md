## CRUD операции с базой данных через PDO

ADD LATER
ALTER TABLE table1 
ADD COLUMN is_deleted TINYINT(1) DEFAULT 0 NOT NULL COMMENT '0 - активно, 1 - удалено';

CREATE INDEX idx_is_deleted ON table1(is_deleted);