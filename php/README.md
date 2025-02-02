## Synchronous execution

> Average execution time 90 - 100 seconds for 1000 requests
> Url - https://jsonplaceholder.typicode.com/posts/1


```bash
php src/synchronous.php
```

## Batched asynchronous execution

> Average execution time 25 - 30 seconds for 1000 requests
> Url - https://jsonplaceholder.typicode.com/posts/1

```bash
php src/batch-async.php
```

## Pooled asynchronous execution

> Average execution time 5 - 10 seconds for 1000 requests
> Url - https://jsonplaceholder.typicode.com/posts/1

```bash
php src/pooled-async.php
```

## Things to note
> Error Handling
    <ul>
        <li>
            Make sure to handle rejections properly using .then() and .otherwise(), or try-catch if using wait()
        </li>
        <li>
            Handle RequestException for 4XXm 5XX errors
        </li>
    </ul>

> Concurrency Management
    <ul>
        <li>
            Use GuzzleHttp\Pool to manage concurrency limits
        </li>
        <li>
            Handle RequestException for 4XXm 5XX errors
        </li>
    </ul>

> Timeouts and Retries
    <ul>
        <li>
            Always set timeouts (timeout, connect_timeout) to avoid hanging requests.
        </li>
    </ul>

> Promise Chaining and Synchronization
    <ul>
        <li>
            If you wait() immediately, it negates the async benefits
        </li>
        <li>
            Chain multiple promises for complex workflows without blocking
        </li>
    </ul>

> Integration with Codeception
    <ul>
        <li>
            Use Guzzle’s MockHandler for unit tests and Codeception's mocking tools for integration tests
        </li>
        <li>
            Ensure assertions are made after promises have settled to avoid false positives
        </li>
    </ul>
