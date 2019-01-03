cd apps/matrix && pwd

export TSH_PORT=6727
export SIZE_ENV=$1
export LD_LIBRARY_PATH=../../mysql-connector/lib

make mtwrkr1

./mworker $2
