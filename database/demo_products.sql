-- Demo Products Data (Ingredients) for Testing

-- Clear existing data (delete in reverse order of foreign key dependencies)
DELETE FROM `meal_recipes`;
DELETE FROM `meals`;
DELETE FROM `products`;

-- Insert ingredients/raw materials
INSERT INTO `products` (`category_id`, `name`, `description`, `sku`, `barcode`, `cost_price`, `selling_price`, `stock`, `min_stock_level`, `max_stock_level`, `unit`, `status`, `created_at`) VALUES
(1, 'All-Purpose Flour', 'High quality wheat flour for baking', 'ING-FLOUR-001', '100001', 15.00, 20.00, 500, 100, 1000, 'kg', 'active', NOW()),
(1, 'Eggs', 'Fresh farm eggs', 'ING-EGGS-001', '100002', 8.00, 12.00, 200, 50, 500, 'dozen', 'active', NOW()),
(1, 'Mozzarella Cheese', 'Fresh mozzarella cheese for pizza', 'ING-MOZZ-001', '100003', 250.00, 300.00, 50, 10, 100, 'kg', 'active', NOW()),
(1, 'Tomato Sauce', 'Italian tomato sauce', 'ING-TOMS-001', '100004', 80.00, 100.00, 100, 20, 200, 'liter', 'active', NOW()),
(1, 'Olive Oil', 'Extra virgin olive oil', 'ING-OLIVE-001', '100005', 200.00, 250.00, 30, 5, 50, 'liter', 'active', NOW()),
(1, 'Pepperoni', 'Sliced pepperoni meat', 'ING-PEPP-001', '100006', 300.00, 350.00, 25, 5, 50, 'kg', 'active', NOW()),
(1, 'Yeast', 'Active dry yeast for dough', 'ING-YEAST-001', '100007', 500.00, 600.00, 10, 2, 20, 'kg', 'active', NOW()),
(1, 'Salt', 'Table salt', 'ING-SALT-001', '100008', 5.00, 10.00, 100, 20, 200, 'kg', 'active', NOW()),
(2, 'Lettuce', 'Fresh green lettuce', 'ING-LETT-001', '100009', 30.00, 50.00, 100, 20, 200, 'kg', 'active', NOW()),
(2, 'Tomatoes', 'Fresh red tomatoes', 'ING-TOM-001', '100010', 40.00, 60.00, 150, 30, 300, 'kg', 'active', NOW()),
(2, 'Cucumber', 'Fresh cucumber', 'ING-CUCU-001', '100011', 25.00, 40.00, 80, 15, 150, 'kg', 'active', NOW()),
(2, 'Feta Cheese', 'Greek feta cheese', 'ING-FETA-001', '100012', 200.00, 250.00, 30, 5, 50, 'kg', 'active', NOW()),
(3, 'Salmon Fillet', 'Fresh salmon fillet', 'ING-SALM-001', '100013', 600.00, 800.00, 15, 3, 20, 'kg', 'active', NOW()),
(3, 'Chicken Breast', 'Boneless chicken breast', 'ING-CHCK-001', '100014', 250.00, 350.00, 50, 10, 100, 'kg', 'active', NOW()),
(3, 'Herbs', 'Mixed cooking herbs', 'ING-HERB-001', '100015', 50.00, 80.00, 20, 5, 50, 'kg', 'active', NOW()),
(4, 'Coffee Beans', 'Premium coffee beans', 'ING-COFF-001', '100016', 300.00, 400.00, 30, 5, 50, 'kg', 'active', NOW()),
(4, 'Milk', 'Fresh milk', 'ING-MILK-001', '100017', 100.00, 120.00, 100, 20, 200, 'liter', 'active', NOW()),
(4, 'Sugar', 'White sugar', 'ING-SUGA-001', '100018', 50.00, 70.00, 200, 50, 500, 'kg', 'active', NOW()),
(4, 'Orange', 'Fresh oranges', 'ING-ORNG-001', '100019', 60.00, 100.00, 100, 20, 200, 'kg', 'active', NOW()),
(5, 'Dark Chocolate', 'High quality dark chocolate', 'ING-CHOC-001', '100020', 200.00, 250.00, 50, 10, 100, 'kg', 'active', NOW()),
(5, 'Cream Cheese', 'Philadelphia cream cheese for cheesecake', 'ING-CREAM-001', '100021', 400.00, 500.00, 20, 5, 40, 'kg', 'active', NOW()),
(5, 'Butter', 'Unsalted butter', 'ING-BUTT-001', '100022', 350.00, 400.00, 30, 5, 60, 'kg', 'active', NOW());

-- Demo Meals Data (Finished dishes)
INSERT INTO `meals` (`category_id`, `name`, `description`, `sku`, `cost_price`, `selling_price`, `status`, `created_at`) VALUES (1, 'Margherita Pizza', 'Fresh mozzarella and tomato pizza', 'MEAL-PIZZA-MARG', 150.00, 650.00, 'active', NOW());
INSERT INTO `meals` (`category_id`, `name`, `description`, `sku`, `cost_price`, `selling_price`, `status`, `created_at`) VALUES (1, 'Pepperoni Pizza', 'Classic pepperoni pizza', 'MEAL-PIZZA-PEPP', 180.00, 750.00, 'active', NOW());
INSERT INTO `meals` (`category_id`, `name`, `description`, `sku`, `cost_price`, `selling_price`, `status`, `created_at`) VALUES (2, 'Caesar Salad', 'Fresh Caesar salad with dressing', 'MEAL-SAL-CAES', 80.00, 450.00, 'active', NOW());
INSERT INTO `meals` (`category_id`, `name`, `description`, `sku`, `cost_price`, `selling_price`, `status`, `created_at`) VALUES (2, 'Greek Salad', 'Traditional Greek salad with feta cheese', 'MEAL-SAL-GREK', 75.00, 420.00, 'active', NOW());
INSERT INTO `meals` (`category_id`, `name`, `description`, `sku`, `cost_price`, `selling_price`, `status`, `created_at`) VALUES (3, 'Grilled Salmon', 'Fresh grilled salmon fillet', 'MEAL-FISH-SALM', 350.00, 1200.00, 'active', NOW());
INSERT INTO `meals` (`category_id`, `name`, `description`, `sku`, `cost_price`, `selling_price`, `status`, `created_at`) VALUES (3, 'Grilled Chicken', 'Herb marinated grilled chicken', 'MEAL-CHCK-GRIL', 150.00, 850.00, 'active', NOW());
INSERT INTO `meals` (`category_id`, `name`, `description`, `sku`, `cost_price`, `selling_price`, `status`, `created_at`) VALUES (4, 'Iced Coffee', 'Cold brew iced coffee', 'MEAL-BEV-ICOF', 35.00, 180.00, 'active', NOW());
INSERT INTO `meals` (`category_id`, `name`, `description`, `sku`, `cost_price`, `selling_price`, `status`, `created_at`) VALUES (4, 'Fresh Orange Juice', 'Freshly squeezed orange juice', 'MEAL-BEV-ORNG', 50.00, 200.00, 'active', NOW());
INSERT INTO `meals` (`category_id`, `name`, `description`, `sku`, `cost_price`, `selling_price`, `status`, `created_at`) VALUES (5, 'Cheesecake', 'Creamy New York style cheesecake', 'MEAL-DESS-CHEE', 100.00, 350.00, 'active', NOW());
INSERT INTO `meals` (`category_id`, `name`, `description`, `sku`, `cost_price`, `selling_price`, `status`, `created_at`) VALUES (5, 'Chocolate Brownie', 'Rich chocolate brownie', 'MEAL-DESS-BROW', 60.00, 280.00, 'active', NOW());

-- Demo Meal Recipes (Link meals to ingredients with quantities)
-- Margherita Pizza (meal_id = 1)
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (1, 1, 0.3);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (1, 3, 0.15);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (1, 4, 0.1);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (1, 5, 0.02);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (1, 7, 0.005);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (1, 8, 0.01);

-- Pepperoni Pizza (meal_id = 2)
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (2, 1, 0.3);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (2, 3, 0.15);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (2, 4, 0.1);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (2, 5, 0.02);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (2, 6, 0.1);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (2, 7, 0.005);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (2, 8, 0.01);

-- Caesar Salad (meal_id = 3)
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (3, 9, 0.15);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (3, 10, 0.1);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (3, 5, 0.03);

-- Greek Salad (meal_id = 4)
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (4, 9, 0.15);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (4, 10, 0.1);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (4, 11, 0.05);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (4, 12, 0.08);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (4, 5, 0.03);

-- Grilled Salmon (meal_id = 5)
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (5, 13, 0.25);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (5, 15, 0.02);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (5, 5, 0.05);

-- Grilled Chicken (meal_id = 6)
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (6, 14, 0.25);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (6, 15, 0.02);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (6, 5, 0.05);

-- Iced Coffee (meal_id = 7)
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (7, 16, 0.015);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (7, 17, 0.2);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (7, 18, 0.02);

-- Fresh Orange Juice (meal_id = 8)
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (8, 19, 0.3);

-- Cheesecake (meal_id = 9)
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (9, 1, 0.2);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (9, 21, 0.3);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (9, 22, 0.15);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (9, 18, 0.1);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (9, 2, 0.08);

-- Chocolate Brownie (meal_id = 10)
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (10, 1, 0.15);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (10, 20, 0.1);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (10, 22, 0.1);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (10, 18, 0.08);
INSERT INTO `meal_recipes` (`meal_id`, `product_id`, `quantity_required`) VALUES (10, 2, 0.04);
