CFLAGS = -O3 
OBJS =-L ../../obj -lsng -L ../../mysql-connector/lib -lmysqlclient
INC =-I ../../include -I ../../mysql-connector/include
SIZE = $(SIZE_ENV)

all: matrix mtclnt1 mtwrkr1 

mtclnt1 : mmaster.c
	cc $(CFLAGS) $(INC) $(INC2) -DN=$(SIZE) -o mmaster mmaster.c $(OBJS) $(OBJS2)

mtwrkr1 : mworker.c
	cc $(CFLAGS) $(INC) $(INC2) -DN=$(SIZE) -o mworker mworker.c $(OBJS) $(OBJS2)

matrix : matrix.c
	cc $(CFLAGS) $(INC) -DN=$(SIZE) -o matrix matrix.c $(OBJS)
