SYS_LINK=-L/usr/lib
INCS = -I../include 
OBJS = ../obj 
FLAGS = -g 
cc = gcc

all : tsh tshtest lsh launch

tsh : tsh.c 
	$(cc) $(EXTRA) $(INCS) $(FLAGS) -o tsh tsh.c -L$(OBJS) -lsng

tshtest : tshtest.c 
	$(cc) $(EXTRA) $(INCS) $(FLAGS) -o tshtest tshtest.c -L$(OBJS) -lsng

lsh : lsh.c
	$(cc) $(EXTRA) $(INCS) $(FLAGS) -o  lsh lsh.c -L$(OBJS) -lsng

launch : launch.c 
	$(cc) $(EXTRA) $(INCS) $(FLAGS) -o launch launch.c -L$(OBJS) -lsng
