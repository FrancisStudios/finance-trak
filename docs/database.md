# Database Structure

The back-end data looks like 

## Tables

You have three important tables for organizing the data  _shown below_ :

1) transactions log 
2) categories list
3) priorities list

And there are one system table:

4) Users


### `1 Transactions log` contains all of your incomes and expenses 

| TID | Year | Month | Day | Amount | Description | Category | Priority |
| --- | ---- | ----- | --- | ------ | ----------- | -------- | -------- |
| 0   | 2025 | 1     | 3   | 1300   | Payday      | 0        | 1        |
| 1   | 2026 | 2     | 4   | -670   | Rent        | 0        | 1        |

**TID:** Transaction ID - a unique, auto increment unsigned integer that identifies transactions

**Year:** Year of the transaction - unsigned small integer

**Month:** Month of the transaction - unsigned tiny integer

**Day:** Day of the transaction - unsigned tiny integer

**Amount:** Amount of the transaction as signed integer -- _negative values represent expenses and positive values incomes_, as one would expect.

**Description:** Description of the transaction - user defined string that should carry information for the user(s) to identify the second party of the transaction or other meaningful data.

**Category:** In this field the __CID__ is listed which is the Category ID in the categories list helper table.

**Priority:** In this field the __PID__ is listed which is the Priority ID in the priorities list helper table.

### `2 Categories list` contains all the user defined categories

| CID | Category  |
| --- | --------- |
| 0   | Hobby     |
| 1   | Groceries |
| 2   | Fuel      |
| 3   | Loan      |
| 4   | Vacation  |

**CID**: unique, unsigned, autoincrement integer that is used as a key in the `transactions log` table to bind categories to transactions. User(s) can create / update / delete categories depending on their account privileges.

**Category:** user defined string

### `3 Priorities list` contains all the user defined priorities

| PID | Priority      |
| --- | ------------- |
| 0   | Mandatory     |
| 1   | Nice to have  |
| 2   | Non essential |

**PID**: unique, unsigned, autoincrement integer that is used as a key in the `transactions log` table to bind priorities to transactions. User(s) can create / update / delete priorities depending on their account privileges.

**Priority:** user defined string

### `4 Users` contain all registered users

Default login: **admin/1234**

**UID:** User ID - unsigned, autoincrement, unique integer

**Username:** user defined string, unique

**Password:** saves a hash of the PW - string

**Privileges:** tiny int which represents four binary values 

| Create | Read | Update | Delete |
| ------ | ---- | ------ | ------ |
| 0      | 1    | 1      | 0      |

And the value of `0b0110` will be represented in a decimal integer - in this case 6 that defines the allowed operations for the user. 

The read flag does nothing - every user can read categories, priorities and all transactions.

**Priorities:** tiny int which represents four binary values 

| Create | Read | Update | Delete |
| ------ | ---- | ------ | ------ |
| 1      | 1    | 0      | 0      |

And the value of `0b0110` will be represented in a decimal integer - in this case 12 that defines the allowed operations for the user. 

The read flag does nothing - every user can read categories, priorities and all transactions.