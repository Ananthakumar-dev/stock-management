import { useState, useEffect } from "react";

const dashboardStatusUrl = route("dashboard.stats");
const Index = () => {
    const [stats, useStats] = useState({
        users: 0, // Total number of users
        stores: 0, // Total number of stores
        items: 0, // Total number of items
        inventories: 0, // Total number of inventories
    });

    useEffect(() => {
        const fetchData = async () => {
            const { data } = await axios.get(dashboardStatusUrl);
            useStats(data);
        };

        fetchData();
    }, []);

    return (
        <div className="py-12">
            <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                {/* Stats Overview */}
                <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    {/* Total Users */}
                    <div className="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                        <div className="p-6">
                            <h3 className="text-lg font-medium text-gray-900 dark:text-gray-200">
                                Total Users
                            </h3>
                            <p className="mt-2 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">
                                {stats.users}
                            </p>
                        </div>
                    </div>

                    {/* Total Stores */}
                    <div className="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                        <div className="p-6">
                            <h3 className="text-lg font-medium text-gray-900 dark:text-gray-200">
                                Total Stores
                            </h3>
                            <p className="mt-2 text-3xl font-semibold text-green-600 dark:text-green-400">
                                {stats.stores}
                            </p>
                        </div>
                    </div>

                    {/* Total Items */}
                    <div className="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                        <div className="p-6">
                            <h3 className="text-lg font-medium text-gray-900 dark:text-gray-200">
                                Total Items
                            </h3>
                            <p className="mt-2 text-3xl font-semibold text-yellow-600 dark:text-yellow-400">
                                {stats.items}
                            </p>
                        </div>
                    </div>

                    {/* Registered Inventories */}
                    <div className="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                        <div className="p-6">
                            <h3 className="text-lg font-medium text-gray-900 dark:text-gray-200">
                                Registered Inventories
                            </h3>
                            <p className="mt-2 text-3xl font-semibold text-red-600 dark:text-red-400">
                                {stats.inventories}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Index;
