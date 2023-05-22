const express = require("express");
const { message } = require("laravel-mix/src/Log");
const users = [];
const app = express();
const server = require("http").createServer(app);
let allowedHost = "localhost"; // the hostname which is allowed to access the backend
let port = 3006; // desired port
let host = "0.0.0.0"; // desired host; 0.0.0.0 to host on your ip
const io = require("socket.io")(server, {
    cors: {
        origin: "http://localhost:3000",
        methods: ["GET", "POST"],
    },
});
const axios = require("axios");
let onlineUsers = [];

const addNewUser = (username, socketId) => {
    !onlineUsers.some((user) => user.username === username) &&
        onlineUsers.push({ username, socketId });
};

const removeUser = (socketId) => {
    onlineUsers = onlineUsers.filter((user) => user.socketId !== socketId);
};

const getUser = (username) => {
    return onlineUsers.find((user) => user.username === username);
};
io.on("connection", (socket) => {
    // socket.on("newUser", (username) => {
    //     addNewUser(username, socket.id);
    //   });
    // console.log("connection");
    // const userList = [];
    // axios
    //     .get("http://127.0.0.1:8000/api/getAllUsers")
    //     .then((response) => {
    //         // userList.push(response.data.data.userId);
    //         console.log("users");
    //         // userList.push(response.data.data);
    //         //console.log(response.data.data);
    //         console.log(response.data.data);
    //         socket.emit("LoginUserList", response.data.data);
    //     })
    //     .catch((error) => {
    //         console.log(error);
    //     });
    // console.log(userList);
    socket.on("newUser", (username) => {
        addNewUser(username, socket.id);
        console.log(onlineUsers);
    });

    socket.on("sendNotification", ({ senderName, receiverName, type }) => {
        const receiver = getUser(receiverName);
        console.log(receiver);
        // io.to(receiver.socketId).emit("getNotification", {
        //     senderName,
        //     type,
        // });
    });

    socket.on("sendText", ({ senderName, receiverName, text }) => {
        const receiver = getUser(receiverName);
        io.to(receiver.socketId).emit("getText", {
            senderName,
            text,
        });
    });

    socket.on("disconnect", () => {
        removeUser(socket.id);
    });
    socket.on("JoinServer", (userName) => {
        const user = {
            userName,
            id: socket.id,
        };
        users.push(user);
        io.emit("new user", user);
    });
    socket.on("JoinRoom", (roomName, users) => {
        socket.join(roomName);
        cb(message[roomName]);
    });
    socket.on("sendChatToServer", (message) => {
        //   console.log(message);
        //i
        const newMess = {
            message: message.message,
            from: message.from,
            to: message.to,
        };
        console.log("check url");
        console.log(newMess);
        axios
            .post("http://127.0.0.1:8000/api/SendMessageToFriend", newMess, {
                headers: {
                    Authorization: "Bearer " + message.token,
                },
            })
            .then((response) => {
                // userList.push(response.data.data.userId);
                //   console.log("users");
                // userList.push(response.data.data);
                //console.log(response.data.data);
                //  console.log(response);
                let newMessage = {
                    connectId: socket.id,
                    message: response.data,
                };
                console.log(newMessage);
                console.log(socket.id);
                io.emit("sendChatToClient", newMessage);
                //  console.log(response.data.data);
                //  socket.emit("LoginUserList", response.data.data);
            })
            .catch((error) => {
                console.log(error);
            });

        // o.sockets.emit("sendChatToClient", message);
        // let message = {
        //     connectId: socket.id,
        //     message: message,
        // };
    });
    // socket.emit("RequestUser", socket.id);
    socket.on("callUser", (data) => {
        io.to(data.userToCall).emit("callUser", {
            signal: data.signalData,
            from: data.from,
            name: data.name,
        });
    });

    socket.on("answerCall", (data) => {
        io.to(data.to).emit("callAccepted", data.signal);
    });

    // socket.on("disconnect", () => {
    //     console.log("Disconnect");
    // });
});

server.listen(port, allowedHost, () => {
    console.log(`The server is running on http://${allowedHost}:${port}`);
});
