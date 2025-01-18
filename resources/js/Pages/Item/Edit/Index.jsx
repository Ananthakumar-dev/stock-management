import React from "react";
import Form from "./Form";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";

const Index = ({ item, measurements }) => {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Edit Item
                </h2>
            }
        >
            <Head title="Edit Item" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                        <Form item={item} measurements={measurements} />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
};

export default Index;
