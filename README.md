# event-machine-graphql

GraphQL Integration for Event Machine

See [event-machine-skeleton](https://github.com/proophsoftware/event-machine-skeleton) for basic integration.

*Note: First versions of Event Machine shipped with GraphQL integreation, but we moved it to an optional package.
The Event Machine core focuses on JSON Schema only and allows the user to make use of all available JSON Schema
functionality. If you want to use GraphQL together with Event Machine you can only use a subset of JSON Schema for
message and read model type definitions. In many cases the subset is suitable enough but supporting JSON Schema and GraphQL
in Event Machine core was a limitation for all users.*
