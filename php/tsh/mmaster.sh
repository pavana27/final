cd apps/matrix && pwd

export SIZE_ENV=$1
export TSH_PORT=6727

export LD_LIBRARY_PATH=../../mysql-connector/lib

make mtclnt1

./mmaster $2 $3
