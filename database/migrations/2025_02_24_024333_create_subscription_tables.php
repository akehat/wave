<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionTables extends Migration
{
    public function up()
    {
        // Users table (with Cashier fields)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'stripe_id')) {
                $table->string('stripe_id')->nullable()->index();
            }
            if (!Schema::hasColumn('users', 'pm_type')) {
                $table->string('pm_type')->nullable();
            }
            if (!Schema::hasColumn('users', 'pm_last_four')) {
                $table->string('pm_last_four', 4)->nullable();
            }
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'created_at') || !Schema::hasColumn('users', 'updated_at')) {
                $table->timestamps();
            }
        });

        // Subscriptions table (from Cashier)
        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                if (!Schema::hasColumn('subscriptions', 'id')) {
                    $table->id();
                }
                if (!Schema::hasColumn('subscriptions', 'user_id')) {
                    $table->unsignedBigInteger('user_id');
                }
                if (!Schema::hasColumn('subscriptions', 'name')) {
                    $table->string('name');
                }
                if (!Schema::hasColumn('subscriptions', 'stripe_id')) {
                    $table->string('stripe_id')->unique();
                }
                if (!Schema::hasColumn('subscriptions', 'stripe_status')) {
                    $table->string('stripe_status');
                }
                if (!Schema::hasColumn('subscriptions', 'stripe_price')) {
                    $table->string('stripe_price')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'quantity')) {
                    $table->integer('quantity')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'trial_ends_at')) {
                    $table->timestamp('trial_ends_at')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'ends_at')) {
                    $table->timestamp('ends_at')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'created_at') || !Schema::hasColumn('subscriptions', 'updated_at')) {
                    $table->timestamps();
                }

                // Add foreign key only if it doesn’t exist (checking constraints is trickier)
                if (!Schema::hasColumn('subscriptions', 'user_id') || !$this->foreignKeyExists('subscriptions', 'user_id')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
            });
        }

        // Plans table
        if (!Schema::hasTable('plans')) {
            Schema::create('plans', function (Blueprint $table) {
                if (!Schema::hasColumn('plans', 'id')) {
                    $table->id();
                }
                if (!Schema::hasColumn('plans', 'name')) {
                    $table->string('name');
                }
                if (!Schema::hasColumn('plans', 'slug')) {
                    $table->string('slug')->unique();
                }
                if (!Schema::hasColumn('plans', 'stripe_plan_id')) {
                    $table->string('stripe_plan_id');
                }
                if (!Schema::hasColumn('plans', 'price')) {
                    $table->decimal('price', 8, 2);
                }
                if (!Schema::hasColumn('plans', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('plans', 'created_at') || !Schema::hasColumn('plans', 'updated_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('users'); // Be cautious with this—see note below
    }

    /**
     * Helper method to check if a foreign key constraint exists (simplified check).
     * Laravel doesn’t provide a direct method, so this is a basic workaround.
     */
    private function foreignKeyExists($table, $column)
    {
        $exists = false;
        try {
            $exists = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableForeignKeys($table)
                ->some(fn($fk) => in_array($column, $fk->getLocalColumns()));
        } catch (\Exception $e) {
            // If an error occurs, assume it doesn’t exist
        }
        return $exists;
    }
}
