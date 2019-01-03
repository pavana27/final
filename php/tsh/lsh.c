/***************************************************************************//**

  @file         main.c

  @author       Stephen Brennan

  @date         Thursday,  8 January 2015

  @brief        LSH (Libstephen SHell). Revised by Justin Y. Shi for remote shell demo. Nov. 2018

*******************************************************************************/

#include <sys/wait.h>
#include <unistd.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
//#include "synergy.h"
void sigchldHandler(/*void*/) ;
 
int bg = 0;  // Background command found 
/*
  Function Declarations for builtin shell commands:
 */
int lsh_cd(char **args);
int lsh_help(char **args);
int lsh_exit(char **args);

/*
  List of builtin commands, followed by their corresponding functions.
 */
char *builtin_str[] = {
  "cd",
  "help",
  "exit"
};

int (*builtin_func[]) (char **) = {
  &lsh_cd,
  &lsh_help,
  &lsh_exit
};

int lsh_num_builtins() {
  return sizeof(builtin_str) / sizeof(char *);
}

int filedes[2];  // For capture fork output

/*
  Builtin function implementations.
*/

/**
   @brief Bultin command: change directory.
   @param args List of args.  args[0] is "cd".  args[1] is the directory.
   @return Always returns 1, to continue executing.
 */
int lsh_cd(char **args)
{
  if (args[1] == NULL) {
    fprintf(stderr, "lsh: expected argument to \"cd\"\n");
  } else {
    if (chdir(args[1]) != 0) {
      perror("lsh");
    }
  }
  return 1;
}

/**
   @brief Builtin command: print help.
   @param args List of args.  Not examined.
   @return Always returns 1, to continue executing.
 */
int lsh_help(char **args)
{
  int i;
  printf("Stephen Brennan's LSH\n");
  printf("Type program names and arguments, and hit enter.\n");
  printf("The following are built in:\n");

  for (i = 0; i < lsh_num_builtins(); i++) {
    printf("  %s\n", builtin_str[i]);
  }

  printf("Use the man command for information on other programs.\n");
  return 1;
}

/**
   @brief Builtin command: exit.
   @param args List of args.  Not examined.
   @return Always returns 0, to terminate execution.
 */
int lsh_exit(char **args)
{
  return 0;
}

/**
  @brief Launch a program and wait for it to terminate.
  @param args Null terminated list of arguments (including program).
  @return Always returns 1, to continue execution.
 */
int lsh_launch(char **args)
{
  pid_t pid, wpid;
  int status, count;

  // Create the pipe for capture stdout of child process
  if (pipe(filedes) == -1) {
	  perror("pipe");
	  exit(1);
  }
  signal(SIGCHLD, sigchldHandler); // Embed SIGCLD handler
  pid = fork();
  if (pid == 0) {
    // Child process
	while ((dup2(filedes[1], STDOUT_FILENO) == -1) && (errno == EINTR)) {};
	close(filedes[1]); // the child does not need these
	close(filedes[0]);
    if (execvp(args[0], args) == -1) {
      perror("lsh");
	  strcpy(shell_out.stdout,"execvp failure");
    }
    exit(EXIT_FAILURE);
  } else if (pid < 0) {
    // Error forking
    perror("lsh");
	strcpy(shell_out.stdout,"fork failure");
  } else {
    // Parent process
	if (bg == 1) {
		signal(SIGCHLD, sigchldHandler);  // Embed SIGCLD handler
		bg = 0; // Reset
		shell_out.pid = pid;
		shell_out.status = 0; // Default
		sprintf(shell_out.stdout,"%d started", pid);
		return 1; // skip the wait
	}
	bzero(shell_out.stdout, sizeof(shell_out.stdout)); // Remove all contents
	close(filedes[1]);
	/* Read from child’s stdout */
	while (1) {
		count = read(filedes[0], shell_out.stdout, sizeof(shell_out.stdout));
		if (count == -1) {
			if (errno == EINTR) {
				perror("Interrupted?");
				continue;
			} else {
				perror("Pipe read error");
				exit(1);
			}
		} else if (count == 0) {
			printf("Nothing to read. count=0 ??? \n");
			break;
		} else {
			printf("Captured[%s](%d)\n",shell_out.stdout, count);
		}	
	}
	close(filedes[0]);
    do {
      wpid = waitpid(pid, &status, WUNTRACED);
    } while (!WIFEXITED(status) && !WIFSIGNALED(status));
	shell_out.pid = pid;
	shell_out.status = status;
  }

  return 1;
}

/**
   @brief Execute shell built-in or launch program.
   @param args Null terminated list of arguments.
   @return 1 if the shell should continue running, 0 if it should terminate
 */
int lsh_execute(char **args)
{
  int i;

  if (args[0] == NULL) {
    // An empty command was entered.
    return 1;
  }

  for (i = 0; i < lsh_num_builtins(); i++) {
    if (strcmp(args[0], builtin_str[i]) == 0) {
      return (*builtin_func[i])(args);
    }
  }

  return lsh_launch(args);
}

#define LSH_RL_BUFSIZE 1024
/**
   @brief Read a line of input from stdin.
   @return The line from stdin.
 
   *** Need to change to socket readn()
   
char *lsh_read_line(void)
{
  int bufsize = LSH_RL_BUFSIZE;
  int position = 0;
  char *buffer = malloc(sizeof(char) * bufsize);
  int c;

  if (!buffer) {
    fprintf(stderr, "lsh: allocation error\n");
    exit(EXIT_FAILURE);
  }

  while (1) {
    // Read a character
    c = getchar();

    // If we hit EOF, replace it with a null character and return.
    if (c == EOF || c == '\n') {
      buffer[position] = '\0';
      return buffer;
    } else {
      buffer[position] = c;
    }
    position++;

    // If we have exceeded the buffer, reallocate.
    if (position >= bufsize) {
      bufsize += LSH_RL_BUFSIZE;
      buffer = realloc(buffer, bufsize);
      if (!buffer) {
        fprintf(stderr, "lsh: allocation error\n");
        exit(EXIT_FAILURE);
      }
    }
  }
}
*/

#define LSH_TOK_BUFSIZE 64
#define LSH_TOK_DELIM " \t\r\n\a"
/**
   @brief Split a line into tokens (very naively).
   @param line The line.
   @return Null-terminated array of tokens.
 */
char **lsh_split_line(char *line)
{
  int bufsize = LSH_TOK_BUFSIZE, position = 0;
  char **tokens = malloc(bufsize * sizeof(char*));
  char *token;

  if (!tokens) {
    fprintf(stderr, "lsh: allocation error\n");
    exit(EXIT_FAILURE);
  }

  token = strtok(line, LSH_TOK_DELIM);
  while (token != NULL) {
    tokens[position] = token;
    position++;

    if (position >= bufsize) {
      bufsize += LSH_TOK_BUFSIZE;
      tokens = realloc(tokens, bufsize * sizeof(char*));
      if (!tokens) {
        fprintf(stderr, "lsh: allocation error\n");
        exit(EXIT_FAILURE);
      }
    }
printf("lsh:split: token(%s) \n", token);
	if (token[0] == '&') bg = 1;
    token = strtok(NULL, LSH_TOK_DELIM);
  }
  tokens[position] = NULL;
  return tokens;
}

void sigchldHandler()
{
   int pid;
   int status;

   while((pid=waitpid(-1,&status,WNOHANG)) > 0)
   {
   	if (WIFEXITED(status)) {
        	status = WEXITSTATUS(status);
        	if (status == NORMAL_EXIT)
                	status = EXITTED;
        	else
                	status = FAILED;
   	} else status = KILLED;
	printf("TSH. subp(%d) terminated (%d) \n", pid, status);
	fflush(stdout);
   } 
   // Reinstall SIGCLD handler
   signal(SIGCHLD, sigchldHandler);
   return;
}
/**
   @brief Loop getting input and executing it.

void lsh_loop(void)
{
  char *line;
  char **args;
  int status;

  do {
    printf("> ");
    line = lsh_read_line();
    args = lsh_split_line(line);
    status = lsh_execute(args);

    free(line);
    free(args);
  } while (status);
}
Removed ***/

/** Removed by JYS Nov. 2018
   @brief Main entry point.
   @param argc Argument count.
   @param argv Argument vector.
   @return status code
 
int main(int argc, char **argv)
{
  // Load config files, if any.

  // Run command loop.
  lsh_loop();

  // Perform any shutdown/cleanup.

  return EXIT_SUCCESS;
}

***/

