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
    // console.log(`New message from ${socketId}: ${username}`);
    !onlineUsers.some((user) => user.username === username) &&
        onlineUsers.push({ username, socketId });
};

const removeUser = (socketId) => {
    onlineUsers = onlineUsers.filter((user) => user.socketId !== socketId);
};

const getUser = (username) => {
    //console.log(username)
    return onlineUsers.find(
        (user) => parseInt(user.username) === parseInt(username)
    );
};
io.on("connection", (socket) => {
    // console.log("login");
    // console.log(socket);
    // console.log(socket.id);
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
    socket.on("FrdsonLine", ({ loginId, userList }) => {
        const checkonline = userList.map((userItem) => {
            const userOn = getUser(userItem.userId);
            console.log(userOn);
            if (userOn != undefined) {
                if (parseInt(userOn.username) === parseInt(userItem.userId)) {
                    let userLeton = {
                        userId: parseInt(userOn.username),
                        userOn: true,
                    };
                    return userLeton;
                } else {
                    let userLeton = {
                        userId: parseInt(userOn.username),
                        userOn: false,
                    };
                    return userLeton;
                }
            } else {
                let userLeton = {
                    userId: userItem.userId,
                    userOn: false,
                };
                return userLeton;
            }
        });
        // const checkonline = onlineUsers.map((user) => {
        //     var useron = [];
        //     // console.log(onlineUsers);
        //     // console.log(user);
        //     // console.log(userList[0].userId);
        //     // console.log(parseInt(user.username));
        //     if (userList[0].userId === parseInt(user.username)) {
        //         let userLeton = {
        //             userId: parseInt(user.username),
        //             userOn: true,
        //         };
        //         return userLeton;
        //     }
        // });
        console.log("frds online");
        console.log(checkonline);
        console.log(onlineUsers);
        const receiver = getUser(loginId);
        console.log("Login User");
        console.log(receiver);
        receiver?.socketId != undefined
            ? io.to(receiver.socketId).emit("getOnlinefrds", checkonline)
            : null;
    });
    socket.on("newUser", (username) => {
        //  console.log(socket);
        // console.log(`New message from ${socket.id}: ${username}`);
        addNewUser(username, socket.id);
        console.log(onlineUsers);
    });

    socket.on(
        "sendNotification",
        ({ senderID, senderName, receiverID, type }) => {
            const receiver = getUser(receiverID);
            if (receiver) {
                io.to(receiver.socketId).emit("getNotification", {
                    senderName,
                    receiverID,
                    type,
                });
            } else {
                console.log(onlineUsers);
                console.log(receiver);
            }
        }
    );
    socket.on("AcceptFriendRequest", async (AuthDetails) => {
        let data = JSON.stringify({
            from: AuthDetails.from,
            to: AuthDetails.to,
            status: "Accept",
        });
        let config = {
            method: "get",
            maxBodyLength: Infinity,
            url: "http://127.0.0.1:8000/api/friendRequestAcceptance",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + AuthDetails.token,
            },
            data: data,
        };

        axios
            .request(config)
            .then((response) => {
                console.log(JSON.stringify(response.data));
                console.log(response.data);
                const receiver = getUser(AuthDetails.from);
                const sender = getUser(AuthDetails.to);
                if (receiver) {
                    const senderName = AuthDetails.toName;
                    const type = "Now both are connected";
                    io.to(receiver.socketId).emit("getNotificationAcceptfrom", {
                        senderName,
                        type,
                    });
                } else {
                    console.log(onlineUsers);
                    console.log(receiver);
                }

                if (sender) {
                    const type = "Accept Your Request";
                    const senderName = AuthDetails.fromName;
                    io.to(sender.socketId).emit("getNotificationAcceptto", {
                        senderName,
                        type,
                    });
                } else {
                    console.log(onlineUsers);
                    console.log(receiver);
                }
            })
            .catch((error) => {
                console.log(error);
            });

        // const res = await axios
        //     .get("http://127.0.0.1:8000/api/friendRequestAcceptance", config)
        //     .then((response) => {
        //         console.log(response.data);
        // const receiver = getUser(AuthDetails.from);
        // if (receiver) {
        //     const senderName = AuthDetails.fromName;
        //     const type = "Accept Your Request";
        //     io.to(receiver.socketId).emit("getNotificationAcceptfrom", {
        //         senderName,
        //         type,
        //     });
        // } else {
        //     console.log(onlineUsers);
        //     console.log(receiver);
        // }
        // const sender = getUser(AuthDetails.to);
        // if (sender) {
        //     const type = "Now both are connected";
        //     const senderName = AuthDetails.toName;
        //     io.to(sender.socketId).emit("getNotificationAcceptto", {
        //         senderName,
        //         type,
        //     });
        // } else {
        //     console.log(onlineUsers);
        //     console.log(receiver);
        // }
        // })
        // .catch((e) => {
        //     console.log(e);
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
