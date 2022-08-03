create database pet_store_rr;
use pet_store_rr;
create table customers (
	customer_id int auto_increment not null,
	first_name varchar(45) not null,
	last_name varchar(45) not null,
	phone varchar(10) unique not null,
	address varchar(60) not null,
	primary key (customer_id)
);
create table animals (
	animal_id int auto_increment not null,
	customer_id int not null,
	age int not null,
	name varchar(45) not null,
	type varchar(45) not null,
	foreign key(customer_id) references customers(customer_id) on delete cascade,
	primary key(animal_id)
);
create table roles (
	role_id int auto_increment not null,
	role varchar(45) not null,
	primary key(role_id)
);
create table employees (
	employee_id int auto_increment not null,
	first_name varchar(45) not null,
	last_name varchar(45) not null,
	phone varchar(10) not null,
	role_id int not null,
	address varchar (60) not null,
	primary key(employee_id),
	foreign key (role_id) references roles(role_id) on delete cascade
);
create table products (
	product_id int auto_increment not null,
	amount int not null,
	product_name varchar(45) not null,
	description varchar(100),
	price int not null,
	primary key(product_id)
);
create table delivery (
	delivery_id int auto_increment not null,
	enovy_id int not null,
	delivery_date date default null,
	delivery_price int not null,
	primary key(delivery_id),
	foreign key (enovy_id) references employees(employee_id)
);
create table orders (
	order_id int auto_increment not null,
	total_price int not null,
	order_date date not null,
	delivery_id int not null,
    customer_id int not null,
    employee_id int not null,
    shipped boolean not null default 0,
    foreign key(employee_id) references employees(employee_id),
    foreign key(customer_id) references customers(customer_id),
	foreign key(delivery_id) references delivery(delivery_id),
	primary key(order_id)
);
create table products_orders (
	order_id int not null,
	product_id int not null,
	quantity int not null,
    foreign key(order_id) references orders(order_id) on delete cascade,
	foreign key(product_id) references products(product_id) on delete cascade,
	primary key (product_id , order_id)
);
create table stock_log (
	log_id int auto_increment not null,
	product_id  int not null,
	change_date date not null,
	employee_id int,
    log_action varchar(45),
    primary key (log_id),
    foreign key(employee_id) references employees(employee_id)
);


/*TRIGGERS*/
SET SQL_SAFE_UPDATES = 0;
/*update log table with every order*/
DELIMITER $$
create trigger log_trigger AFTER INSERT ON products_orders FOR EACH ROW BEGIN
UPDATE products set amount = amount - new.quantity where 
new.product_id=products.product_id;
INSERT INTO stock_log (product_id,employee_id,change_date,log_action) 
values 
(new.product_id, (select employee_id from orders where new.order_id=orders.order_id),
(select order_date from orders where new.order_id=orders.order_id),'amount changed');
END $$
DELIMITER ;
SET SQL_SAFE_UPDATES = 1;

/*update log table with every addirion of new product*/
DELIMITER $$
create trigger new_product AFTER INSERT ON products FOR EACH ROW BEGIN
INSERT INTO stock_log (product_id,employee_id,change_date,log_action) 
values 
(new.product_id, null, current_date ,'new product');
END $$
DELIMITER ;

/*update log table with every delete of order*/
DELIMITER $$
create trigger delete_product AFTER DELETE ON products FOR EACH ROW BEGIN
INSERT INTO stock_log (product_id,employee_id,change_date,log_action) 
values 
(old.product_id, null, current_date(),'delete ftom stock');
END $$
DELIMITER ;



insert into customers values 
(2,'dana','cohen','0541234568','tel-aviv pinkas 14'), (3,'roy','kushmaro','0541234562','tel-aviv lavontin 9'), 
(4,'adi','david','0541234566','tel-aviv rashi 18'), (5,'rina','catz','0541234563','tel-aviv zabutinski 25'),
(6,'ran','levi','0541234560','tel-aviv zamir 11'), (1,'yosi','levi','0541234567','tel-aviv rokah 12'), 
(7,'daniel','cohen','0541234511','tel-aviv hadar 16'), (8,'adam','levine','0541234532','tel-aviv hakishon 3'), 
(9,'gil','bar','0541234522','tel-aviv haliya 8'), (10,'bill','gates','0541234543','tel-aviv even gvirol 29'),
(11,'katy','perry','0586664543','tel-aviv david 118');
insert into animals values 
(1,1,3, 'roko','parrot'),(2,2,2,'sonia','cat'),(3,3,10,'rexi','bulldog'),(4,4,1,'mitzi','cat'),(5,5,4,'chiko','snake'),
(6,6,12,'levana','cat'),(7,7,5,'lolita','chineese pug'),(8,8,9,'dyego', 'dog (involoved)'),(9,9,3,'bamba','hamster'),
(10,10,5,'simba','labrador'),(11,3,7,'buddy','bulldog'),(12,1,8,'stalin','russian cat');
insert into roles values (1,'seller'),(2,'enovy'),(3,'storekeeper');
insert into employees values
(1,'uri','cohen','0541234512',1,'tel-aviv krintski 1'), (2,'dina','azulai','0541234333',1,'tel-aviv arlozorov 2'), 
(3,'din','dan','0520234566',1,'tel-aviv hess 20'), (4,'dima','vasili','0541234443',1,'tel-aviv laskov 24'),
(5,'nikol','blich','0529234560',1,'tel-aviv geula 10'), (6,'noa','kirel','0555555555',2,'tel-aviv bavli 142'), 
(7,'udi','bialik','0525234511',2,'tel-aviv balfour 111'), (8,'anna','zak','0547774532',2,'tel-aviv sokolov 33'), 
(9,'bar','shemesh','0523234522',3,'tel-aviv maze 49'), (10,'eilon','tesla','0541884543',3,'tel-aviv even engel 129');
insert into products values
(1,200,'bones','bones for chewing',10),(2,300,'bonzo','dogs food',25),(3,100,'comb','hair grooming product',14),
(4,150,'roller','for taking off hair from clothes',7),(5,100,'fish in a can', 'cats treat',10),(6,50,'collar',null,70),
(7,120,'coat',null,30),(8,140,'biscuits','dogs and cats treats',5),(9,60,'bed','comfortable bad for cats and dogs',40),
(10,10,'cage','cage for reptiles',45),(11,3,'aquarium',null,200), (12,30,'Running wheel',null,20); 
insert into delivery values
(1,7,null,0),(2,7,null,0),(5,6,null,30),(4,7,null,10),(3,7,null,0),
(7,6,'2022-02-04',30),(8,8,'2022-03-10',25),(9,8,'2022-01-10',0),(10,8,'2022-01-08',20),(6,6,'2022-02-08',15);
insert into orders 
(order_id, total_price, order_date, delivery_id, customer_id, employee_id)
values
(1,200,'2022-01-05',1,1,1),(2,250,'2022-01-09',2,2,1),(3,300,'2022-03-08',3,3,2),(4,40,'2022-02-16',4,4,1),
(5,120,'2022-02-01',5,4,4),(6,60,'2022-02-06',6,5,2),(7,120,'2022-02-02',7,6,4),(8,100,'2022-03-07',8,7,5),
(9,200,'2022-01-10',9,9,1),(10,180,'2022-01-07',10,10,3);
insert into products_orders values
(1,1,5),(1,2,4),(1,8,10),(2,11,1),(2,2,2),(3,6,3),(3,7,1),(4,1,4),(5,5,10),(5,8,4),
(6,7,2),(7,1,3),(7,3,5),(8,4,10),(8,7,1),(9,12,10),(10,9,2),(10,2,4);

-- queries:
-- 1
select product_name,amount from products;
-- 2
select * from orders where week(order_date)=week(now())-500; /*1 is instead of x*/
select * from orders  WHERE order_date >= curdate() - INTERVAL DAYOFWEEK(curdate())+7*24 DAY;
-- 3
select sum(products_orders.quantity) as quantity, employee_id,first_name,last_name from 
employees
inner join orders using(employee_id)
inner join products_orders using(order_id)
group by employee_id limit 1;
-- 4
select sum(orders.total_price) as money, employee_id,first_name,last_name from 
employees
inner join orders using(employee_id)
group by employee_id limit 1;
-- 5
select order_id,first_name,last_name from
customers inner join orders using(customer_id) WHERE shipped = 0;
-- 6
select first_name,last_name,customer_id from customers where customer_id not in (select customer_id from orders);
-- 7
select first_name,last_name, count(order_id),customer_id from
customers inner join orders using(customer_id)
group by customer_id having count(order_id)>1;
-- 8
select sum(total_price) from orders where month(order_date)=month(now())-4; /*1 is instead of x*/

-- FUNCTION
set global log_bin_trust_function_creators =  1;
DELIMITER $$
CREATE FUNCTION
	total_count(in_first_name VARCHAR(45),in_mount integer, in_year integer)  RETURNS INTEGER
BEGIN
DECLARE s_count INTEGER default 0;
SELECT 
    sum(orders.total_price)
INTO s_count FROM
    employees
        INNER JOIN
    orders ON employees.employee_id = orders.employee_id
WHERE
    employees.first_name = in_first_name
    AND YEAR(order_date) = in_year
        AND MONTH(order_date) = in_mount;
        
RETURN s_count;

END$$

DELIMITER ;
--------------------------------------------------------

DELIMITER $$ 
CREATE PROCEDURE set_t_order(in_order_id INT)
BEGIN
	DECLARE delivery_p INTEGER default 0;
		UPDATE orders 
	SET 
		total_price =  t_order(in_order_id)
	WHERE
    order_id = in_order_id;
	if ((select total_price from orders where order_id = in_order_id) < 180) then
		set delivery_p = (select total_price from orders where order_id = in_order_id)/4 ;
		UPDATE orders SET 
		total_price = total_price + delivery_p
		where order_id = in_order_id;
		UPDATE delivery SET 
		delivery_price = delivery_p
		where delivery.delivery_id = (select delivery_id from orders where order_id = in_order_id) ;
	end if;
END $$
DELIMITER ;
---------------------------------------------------
DELIMITER ;
set global log_bin_trust_function_creators =  1;
DELIMITER $$
CREATE FUNCTION
	t_order(in_order integer)  RETURNS INTEGER
BEGIN
DECLARE s_count INTEGER default 0;
SELECT 
   sum(products.price* products_orders.quantity)
INTO s_count FROM
    products
inner join products_orders using (product_id)
inner join orders using (order_id)
where order_id=in_order;
RETURN s_count;

END$$
DELIMITER ;

-- 1
DELIMITER $$
CREATE PROCEDURE update_delivery (IN in_order_id int , IN in_employee_id int)
begin
update orders set shipped=1 where orders.order_id=in_order_id;
update delivery set delivery_date= curdate()
where delivery.delivery_id = (select delivery_id from orders where orders.order_id=in_order_id);
update delivery set enovy_id= in_employee_id
where delivery.delivery_id = (select delivery_id from orders where orders.order_id=in_order_id);
END $$
DELIMITER ;

-- 2

DELIMITER $$ 

CREATE PROCEDURE best_seller(in_num INT, in_days INT)
BEGIN
	SELECT p.product_id, p.product_name, p.price, count(p.product_id) as p_sum FROM products p
		INNER JOIN products_orders po
		ON 
			p.product_id = po.product_id
            INNER JOIN  orders o
		ON 
			o.order_id = po.order_id
		WHERE o.order_date >= curdate() - INTERVAL in_days DAY
		GROUP BY p.product_id
		ORDER BY count(p.product_id) DESC LIMIT in_num;

END; $$

DELIMITER ;

-- 3

DELIMITER $$ 

CREATE PROCEDURE discount(in_order_id INT, in_percent INT)
BEGIN
	UPDATE store_order
	SET
		price = price - (price * in_percent / 100 )
	where order_id = in_order_id;

END; $$

DELIMITER ;


/*check trigger 1*/
insert into orders (order_id, total_price, order_date, delivery_id, customer_id, employee_id)
values (11,200,'2022-02-10',9,9,1);
insert into products_orders values (11,12,10);
select * from products where product_id=12;

/*check trigger 2*/
insert into products (amount,product_name,description,price)
values (10,'trip bag','small bag for sakakis and treats',20);

/*check trigger 3*/
insert into products (amount,product_name,description,price)
values (100,'tooth paste','brightning and cleaning your dog teeth',40);
SET SQL_SAFE_UPDATES = 0;
delete from products where product_name='tooth paste';
SET SQL_SAFE_UPDATES = 1;

