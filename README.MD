# Тестовое задание: Получение списка заказов с фильтрацией, сортировкой и пагинацией

Привет!
Не пугайся, пожалуйста, большого количества файлов в проекте — это в основном инфраструктура и Value Objects, которые уже написаны и работают из коробки. Тебе не придётся всё переписывать с нуля или глубоко разбираться в каждом классе.
На самом деле основная задача это классический кейс на multi-database + фильтрацию + пагинацию, но в контексте DDD архитектуры.

Не запрещается пользоваться нейросетями - мы прекрасно понимаем, что ими сейчас пользуются практически все разработчики.

Когда закончишь:
- упакуй проект в ZIP-архив (с твоими изменениями)
- загрузи архив на Яндекс.Диск
- пришли нам ссылку на скачивание (убедись, что доступ открыт по ссылке)

Ждём твоё решение :)

## Цель задания
Реализовать метод получения списка заказов с учётом фильтров, сортировки и пагинации.  
Данные о заказах и продуктах хранятся в одной базе данных, а информация о произведённом количестве — в другой базе (разные контейнеры, разные подключения).  
Прямые JOIN-ы между базами невозможны.

## Требования к реализации

- Реализовать метод  
  `OrderQueryService::getFilteredPaginatedOrders(...)`  
  так, чтобы он возвращал объект `FilteredPaginatedOrders`.

- Входные параметры метода:
    - `OrderFilterParams $filters`
    - `OrderSortingParams $sorting`
    - `PaginationParams $pagination`
    - `DateTimeZone $clientTimezone`

- Выход:
    - `FilteredPaginatedOrders`
        - массив объектов `OrderData`
        - объект `PaginationMetadata` (currentPage, perPage, total, lastPage)

## Основные сущности и поля

**Order** (первая БД)
- id (uuid string)
- title (название заказа 1-200 символов)
- product_id (uuid)
- product_quantity (целевое количество, int, минимум 1)
- priority (low / normal / high)
- deadline_at (datetime string или null)
- created_at (datetime string)

**Product** (первая БД)
- id (uuid)
- name (название продукта 1-200 символов)

**OrderProgress** (вторая БД)
- id (uuid)
- order_id (ссылка на Order.id)
- product_quantity (произведённое количество за эту запись, int)
- created_at (datetime string)

Для каждого заказа нужно вычислить:

- **producedQuantity** = сумма `product_quantity` по всем записям OrderProgress с соответствующим order_id
- **productionProgress** = процент выполнения заказа, округлённый до целого числа:  
  `(producedQuantity / product_quantity) × 100`

## Поля в OrderData (результат)

- id → OrderId
- title → OrderTitle
- priority → OrderPriority
- productId → ProductId
- productName → ProductName (из таблицы Product)
- targetQuantity → ProductQuantity (из Order)
- producedQuantity → ProductQuantity (вычисляется из OrderProgress)
- productionProgress → ProductionProgress (вычисляется из Order и OrderProgress)
- deadlineAt → ?DateTimeImmutable
- createdAt → DateTimeImmutable

## Фильтры (OrderFilterParams)

- priorities: OrderPriority[] (пустой массив → не фильтровать)
- products: ProductId[] (пустой массив → не фильтровать)
- deadlineStartDate / deadlineEndDate: ?DateTimeImmutable (null → не фильтровать)
- createdStartDate / createdEndDate: ?DateTimeImmutable (null → не фильтровать)
- isProductionStarted: bool — true, если произведено хотя бы 1 шт. (producedQuantity > 0)
- isProductionCompleted: bool — true, если произведено ≥ целевого количества

## Сортировка (OrderSortingParams)

Разрешённые поля:
- title
- product_name
- deadline_at
- created_at
- production_progress

Направление: asc / desc

## Пагинация

- page ≥ 1
- perPage: 20, 40 или 60
- Нужно вернуть корректный total и lastPage

## Важные моменты

- Две разные базы → запросы выполняются отдельно.
- Стратегия выборки (с какой базы начинать) зависит от фильтров.
- Нужно минимизировать количество запросов и объём передаваемых данных.
- Обработка дат должна учитывать переданную клиентскую таймзону, в базах всё время хранится в UTC.
- Все Value Objects уже валидируют себя сами (не нужно дополнительно проверять), если потребуется добавить свои VO, их поведение должно быть аналогичным.
- Ошибки валидации пагинации и значений должны бросать соответствующие исключения, наследуемые от BusinessException.

## Где писать код

Основная логика — в `OrderQueryService::getFilteredPaginatedOrders`

Также нужно доработать:

- `FirstDatabaseProvider::getDataForFilteredAndPaginatedOrders`
- `SecondDatabaseProvider::getDataForFilteredAndPaginatedOrders`
- Модели Eloquent (добавить методы, отношения)

## Ожидаемый результат

Рабочий код, который:
- правильно фильтрует
- корректно считает producedQuantity
- правильно пагинирует и сортирует
- возвращает ожидаемую структуру DTO

Удачи!
